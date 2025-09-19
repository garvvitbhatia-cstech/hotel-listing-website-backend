<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Languages;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
	private static $User;
    private static $TokenHelper;
	private static $Orders;
	public function __construct(){
		self::$User = new User();
        self::$TokenHelper = new TokenHelper();
	}

    #admin dashboard page
    public function getList(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        return view('/admin/users/index');
    }
    public function listPaginate(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        $query = self::$User->where('status', '!=', 3)->where('type', 'User');

		if($request->input('name')  && $request->input('name') != ""){
            $name = $request->input('name');
            $query->where('name', 'like', '%'.$name.'%');
		}
		if($request->input('email')  && $request->input('email') != ""){
            $email = $request->input('email');
            $query->where('email', 'like', '%'.$email.'%');
		}
		if($request->input('mobile')  && $request->input('mobile') != ""){
            $mobile = $request->input('mobile');
            $query->where('mobile', 'like', '%'.$mobile.'%');
		}
		if($request->session()->has('filter_dashboard_condition')){
            $filter_conditions = $request->session()->get('filter_dashboard_condition');
			$vendor_id = Session::get('admin_id');
			$type = Session::get('admin_type');
			$setDatas = '';
			$queryOrd = self::$Orders->where('invoice_id', '!=', "");
			if($type == 'Vendor'){
				$queryOrd->where('vendor_id',$vendor_id);
			}
			if($filter_conditions['type'] == 'total_customers2'){
				if($filter_conditions['startDate'] != '' && $filter_conditions['endDate'] != ''){
					$queryOrd->whereBetween('orders.created_at' ,[$filter_conditions['startDate'], $filter_conditions['endDate']]);
				}
			}			
			if($filter_conditions['type'] == 'total_customers'){
				$queryOrd->whereMonth('orders.created_at' , '=' , $filter_conditions['month']);
				$queryOrd->whereYear('orders.created_at' , '=' , $filter_conditions['year']);
			}
			$queryOrd->groupBy('orders.user_id');
			$orders_data = $queryOrd->select('orders.user_id')->get();
			$setDatas = array();
			foreach($orders_data as $key => $value){
				$data = DB::table('users')->where(array('id' => $value->user_id,'type' => 'User'))->where('status','!=',3)->first();
				if(isset($data->id)){
					$setDatas[] = $value->user_id;
				}
			}
			$implode = implode(',',array_unique($setDatas));
			$query->whereRaw('FIND_IN_SET(id, ?)', [$implode]);
		}
		$records =  $query->orderBy('id', 'DESC')->simplePaginate(20);
        return view('/admin/users/paginate',compact('records'));
    }

    #add new Service Type
    public function addPage(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
		if($request->input()){
			$validator = Validator::make($request->all(), [
                'name' => 'required',
				'email' => 'required|email|unique:users,email',
                'password' => 'required|min:4',
                'confirm_password' => 'required|same:password',
                'mobile' => 'required|min:10'
            ],[
                'name.required' => 'Please enter name.',
				'email.required' => 'Please enter email.',
				'email.email' => 'Please enter valid email.',
				'email.unique' => 'Email already exists.',
                'password.required' => 'Please enter password.',
                'confirm_password.required' => 'Please enter confirm password.',
                'mobile.required' => 'Please enter contact number.',
                'mobile.min' => 'Please enter valid contact number.'
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('name')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('name')));die;
				}
				if($errors->first('email')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('email')));die;
				}
                if($errors->first('mobile')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('mobile')));die;
				}
                if($errors->first('password')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('password')));die;
				}
                if($errors->first('confirm_password')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('confirm_password')));die;
				}
			}else{
                if(!self::$User->ExistingRecord($request->input('email'))){
                    $setData['type'] = 'User';
                    $setData['name'] = $request->input('name');
					$setData['email'] = $request->input('email');
					$setData['mobile'] = $request->input('mobile');
					$setData['gender'] = $request->input('gender');
					$setData['address'] = $request->input('address');
					$setData['city'] = $request->input('city');
					$setData['zipcode'] = $request->input('zipcode');
                    $password = password_hash($request->post('password'),PASSWORD_BCRYPT);
                    $setData['password'] = $password;
                    $setData['emergency_contact_name'] = $request->input('emergency_contact_name');
					$setData['emergency_contact_number'] = $request->input('emergency_contact_number');

                    #set billing Address
                    $billing_details['Billing']['first_name'] = $request->input('billing_first_name');
                    $billing_details['Billing']['last_name'] = $request->input('billing_last_name');
                    $billing_details['Billing']['phone'] = $request->input('billing_phone');
                    $billing_details['Billing']['address'] = $request->input('billing_address');
                    $billing_details['Billing']['city'] = $request->input('billing_city');
                    $billing_details['Billing']['state'] = $request->input('billing_state');
                    $billing_details['Billing']['country'] = $request->input('billing_country');
                    $billing_details['Billing']['zipcode'] = $request->input('billing_zipcode');
                    $billing_details['Shipping']['first_name'] = $request->input('billing_first_name');
                    $billing_details['Shipping']['last_name'] = $request->input('billing_last_name');
                    $billing_details['Shipping']['phone'] = $request->input('billing_phone');
                    $billing_details['Shipping']['address'] = $request->input('billing_address');
                    $billing_details['Shipping']['city'] = $request->input('billing_city');
                    $billing_details['Shipping']['state'] = $request->input('billing_state');
                    $billing_details['Shipping']['country'] = $request->input('billing_country');
                    $billing_details['Shipping']['zipcode'] = $request->input('billing_zipcode');
                    if($request->input('sameAddress') == "No"){
                        $billing_details['Shipping']['first_name'] = $request->input('shipping_first_name');
                        $billing_details['Shipping']['last_name'] = $request->input('shipping_last_name');
                        $billing_details['Shipping']['phone'] = $request->input('shipping_phone');
                        $billing_details['Shipping']['address'] = $request->input('shipping_address');
                        $billing_details['Shipping']['city'] = $request->input('shipping_city');
                        $billing_details['Shipping']['state'] = $request->input('shipping_state');
                        $billing_details['Shipping']['country'] = $request->input('shipping_country');
                        $billing_details['Shipping']['zipcode'] = $request->input('shipping_zipcode');
                    }
                    $setData['billing_details'] = json_encode($billing_details);

                    if(isset($request->profile_image) && $request->profile_image->extension() != ""){
                        $validator = Validator::make($request->all(), [
                            'profile_image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'
                        ]);
                        if($validator->fails()){
                            $errors = $validator->errors();
                            return json_encode(array('heading'=>'Error','msg'=>$errors->first('profile_image')));die;
                        }else{
							$actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('profile_image')).'.'.$request->profile_image->extension());
                            $destination = base_path().'/public/img/users/';
                            $request->profile_image->move($destination, $actual_image_name);
                            $setData['photo'] = $actual_image_name;
                        }
                    }
                    $record = self::$User->CreateRecord($setData);
                }
                echo json_encode(array('heading'=>'Success','msg'=>'Customer added successfully'));die;
			}
		}
		return view('/admin/users/add-page');
    }

    #edit Service Type
    public function editPage(Request $request, $row_id){
		$RowID =  base64_decode($row_id);
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

        if($request->input()){
			$validator = Validator::make($request->all(), [
                'name' => 'required',
				'email' => 'required|email|unique:users,email,'.$RowID,
                'mobile' => 'required|min:10',
            ],[
                'name.required' => 'Please enter name.',
				'email.required' => 'Please enter email.',				
				'email.email' => 'Please enter valid email.',
				'email.unique' => 'Email already exists.',
                'mobile.required' => 'Please enter contact number.',
                'mobile.min' => 'Please enter valid contact number.',
            ]);
			if($validator->fails()){
				$errors = $validator->errors();
				if($errors->first('name')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('name')));die;
				}
				if($errors->first('email')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('email')));die;
				}
                if($errors->first('mobile')){
                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('mobile')));die;
				}
			}else{
                //profile image
                if(self::$User->ExistingRecordUpdate($request->input('email'), $RowID)){
                    echo json_encode(array('heading'=>'Error','msg'=>'Customer already exists.'));die;
                }else{
                    $setData['id'] =  $RowID;
                    $setData['name'] = $request->input('name');
					$setData['email'] = $request->input('email');
					$setData['mobile'] = $request->input('mobile');
					$setData['gender'] = $request->input('gender');
					$setData['address'] = $request->input('address');
					$setData['city'] = $request->input('city');
					$setData['zipcode'] = $request->input('zipcode');
                    $setData['emergency_contact_name'] = $request->input('emergency_contact_name');
					$setData['emergency_contact_number'] = $request->input('emergency_contact_number');
                    #set billing Address
                    $billing_details['Billing']['first_name'] = $request->input('billing_first_name');
                    $billing_details['Billing']['last_name'] = $request->input('billing_last_name');
                    $billing_details['Billing']['phone'] = $request->input('billing_phone');
                    $billing_details['Billing']['address'] = $request->input('billing_address');
                    $billing_details['Billing']['city'] = $request->input('billing_city');
                    $billing_details['Billing']['state'] = $request->input('billing_state');
                    $billing_details['Billing']['country'] = $request->input('billing_country');
                    $billing_details['Billing']['zipcode'] = $request->input('billing_zipcode');
                    $billing_details['Shipping']['first_name'] = $request->input('billing_first_name');
                    $billing_details['Shipping']['last_name'] = $request->input('billing_last_name');
                    $billing_details['Shipping']['phone'] = $request->input('billing_phone');
                    $billing_details['Shipping']['address'] = $request->input('billing_address');
                    $billing_details['Shipping']['city'] = $request->input('billing_city');
                    $billing_details['Shipping']['state'] = $request->input('billing_state');
                    $billing_details['Shipping']['country'] = $request->input('billing_country');
                    $billing_details['Shipping']['zipcode'] = $request->input('billing_zipcode');
                    if($request->input('sameAddress') == "No"){
                        $billing_details['Shipping']['first_name'] = $request->input('shipping_first_name');
                        $billing_details['Shipping']['last_name'] = $request->input('shipping_last_name');
                        $billing_details['Shipping']['phone'] = $request->input('shipping_phone');
                        $billing_details['Shipping']['address'] = $request->input('shipping_address');
                        $billing_details['Shipping']['city'] = $request->input('shipping_city');
                        $billing_details['Shipping']['state'] = $request->input('shipping_state');
                        $billing_details['Shipping']['country'] = $request->input('shipping_country');
                        $billing_details['Shipping']['zipcode'] = $request->input('shipping_zipcode');
                    }
                    $setData['billing_details'] = json_encode($billing_details);

                    if(isset($request->profile_image) && $request->profile_image->extension() != ""){
                        $validator = Validator::make($request->all(), [
                            'profile_image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'
                        ]);
                        if($validator->fails()){
                            $errors = $validator->errors();
                            return json_encode(array('heading'=>'Error','msg'=>$errors->first('profile_image')));die;
                        }else{
							$actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('profile_image')).'.'.$request->profile_image->extension());
                            $destination = base_path().'/public/img/users/';
                            $request->profile_image->move($destination, $actual_image_name);
                            $setData['photo'] = $actual_image_name;
                            if($request->input('old_profile_image') != ""){
                                if(file_exists($destination.$request->input('old_profile_image'))){
                                    unlink($destination.$request->input('old_profile_image'));
                                }
                            }
                        }
                    }
                    self::$User->UpdateRecord($setData);
                }
                echo json_encode(array('heading'=>'Success','msg'=>'Customer updated successfully'));die;
			}
		}
		$rowData = self::$User->where(array('id' => $RowID))->first();
        if(isset($rowData->id)){
            if(!empty($rowData->billing_details)){
                $rowData->billing_details = json_decode($rowData->billing_details, true);
            }
            return view('/admin/users/edit-page',compact('rowData','row_id'));
        }else{
            return redirect('/admin/users');
        }
    }
}
