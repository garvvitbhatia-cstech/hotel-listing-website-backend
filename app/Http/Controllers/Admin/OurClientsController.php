<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\OurClients;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class OurClientsController extends Controller {
	
    private static $OurClients;
    private static $TokenHelper;
    public function __construct(){
        self::$OurClients = new OurClients();
        self::$TokenHelper = new TokenHelper();
    }
    #admin dashboard page
    public function getList(Request $request){
        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }
        return view('/admin/our_clients/index');
    }
    public function listPaginate(Request $request){
        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }
        $query = self::$OurClients->where('status', '!=', 3);
        if ($request->input('name') && $request->input('name') != ""){
            $name = $request->input('name');
            $query->where('name', 'like', '%' . $name . '%');
        }
        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);
        return view('/admin/our_clients/paginate', compact('records'));
    }
    #add new Service Type
    public function addPage(Request $request){
        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }
        if ($request->input()){
            $validator = Validator::make($request->all(), [
				'name' => 'required', 
				'image' => 'required'
			], [
				'name.required' => 'Please enter name.', 
				'image.required' => 'Please select image.'
			]);
            if ($validator->fails()){
                $errors = $validator->errors();
                if ($errors->first('name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name')));
                    die;
                }
                if ($errors->first('image')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                    die;
                }
            } else {
                if (isset($request->image) && $request->image->extension() != ""){
                    $validator = Validator::make($request->all(), ['image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);
                    if ($validator->fails()){
                        $errors = $validator->errors();
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                        die;
                    } else {
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true) . mt_rand(100001, 999999)) . uniqid(mt_rand() . true) . $request->file('image')) . '1.' . $request->image->extension());
                        $destination = base_path() . '/public/admin/images/teams/';
                        $request->image->move($destination, $actual_image_name);
                        $setData['image'] = $actual_image_name;
                    }
                }
                $setData['description'] = $request->input('description');
                $setData['name'] = $request->input('name');
                $record = self::$OurClients->CreateRecord($setData);
                echo json_encode(array('heading' => 'Success', 'msg' => 'Client added successfully'));
                die;
            }
        }
        return view('/admin/our_clients/add-page');
    }
    #edit Service Type
    public function editPage(Request $request, $row_id){
        $RowID = base64_decode($row_id);
        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }
        $rowData = self::$OurClients->where(array('id' => $RowID))->first();
        if ($request->input()){
            $validator = Validator::make($request->all(), [
				'name' => 'required',
			], [
				'name.required' => 'Please enter name.',
			]);
            if ($validator->fails()){
                $errors = $validator->errors();
                if ($errors->first('name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('name')));
                    die;
                }
            } else {
                if (isset($request->image) && $request->image->extension() != ""){
                    $validator = Validator::make($request->all(), ['image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);
                    if($validator->fails()){
                        $errors = $validator->errors();
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                        die;
                    }else{
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true) . mt_rand(100001, 999999)) . uniqid(mt_rand() . true) . $request->file('image')) . '2.' . $request->image->extension());
                        $destination = base_path() . '/public/admin/images/teams/';
                        $request->image->move($destination, $actual_image_name);
                        $setData['image'] = $actual_image_name;
                        if($rowData->image != ""){
                            if(file_exists($destination . $rowData->image)){
                                unlink($destination . $rowData->image);
                            }
                        }
                    }
                }
                $setData['id'] = $RowID;
                $setData['description'] = $request->input('description');
                $setData['name'] = $request->input('name');
                self::$OurClients->UpdateRecord($setData);
            }
            echo json_encode(array('heading' => 'Success', 'msg' => 'Client updated successfully'));
            die;
        }
        if (isset($rowData->id)){
            return view('/admin/our_clients/edit-page', compact('rowData', 'row_id'));
        } else {
            return redirect('/admin/our-clients');
        }
    }
	
}