<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MembershipRequests;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Languages;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;
use Mpdf\Mpdf;


class MembershipRequestController extends Controller {
	
    private static $MembershipRequests;

    private static $TokenHelper;

    public function __construct(){

        self::$MembershipRequests = new MembershipRequests();

        self::$TokenHelper = new TokenHelper();

    }
	public function generatePdf(Request $request){
		$rowData = self::$MembershipRequests->where('id',$request->id)->first();
		$html = '<div style="background-color:#201F1F; text-align:center; padding:20px;"><img src="https://luxiday.com/assets/img/logo.png" style="width: 100px;"/></div><div style="align-items: center; justify-content: center; gap:30px; margin-left:100px;"><br><br>
		
        <div style="background: url(https://luxiday.com/admin/storage/pdf/bronze_member.png) no-repeat 0 0; background-size:100%; padding:24px 20px 30px 20px; width: 420px; min-height: 238px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h3 style="color: #fff; font-size: 1.5rem; font-weight: 300; letter-spacing: 1px; margin: 0; font-family:tahoma">'.$rowData->hotel_name.'</h3>
                <div style="display: inline-flex;padding-left:350px;"><img src="https://luxiday.com/admin/public/admin/images/teams/'.$rowData->image.'" style="width: 60px; height: 60px;"/></div>
            </div>
            <div style="padding: 0px 0 48px 0;">
                <h2 style="font-size: 1.52rem; font-weight: 400; margin-top: 10px; margin-bottom: 10px; color: #fff; letter-spacing: 1px;font-family:tahoma">'.$rowData->username.'</h2>
                <p style="padding:0; margin:0 0; color:#fff; font-size:1rem; font-weight: 300; text-transform: uppercase;font-family:tahoma">Validity</p>
                <h4 style="margin: 0px 0; color: #fff;font-size: 1.52rem;font-weight: 300;font-family:tahoma">'.date('d/m/Y',strtotime($rowData->validity)).'</h4>
            </div>

        </div><br><br>

        <div style="background: url(https://luxiday.com/admin/storage/pdf/sliver_member.png) no-repeat 0 0; background-size:100%; padding:24px 20px 30px 20px; width: 420px; min-height: 238px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h3 style="color: #181818; font-size: 1.5rem; font-weight: 300; letter-spacing: 1px; margin: 0;font-family:tahoma">'.$rowData->hotel_name.'</h3>
                <div style="display: inline-flex;padding-left:350px;"><img src="https://luxiday.com/admin/public/admin/images/teams/'.$rowData->image.'" style="width: 60px; height: 60px;"/></div>
            </div>
            <div style="padding: 0px 0 48px 0;">
                <h2 style="font-size: 1.52rem; font-weight: 400; margin-top: 10px; margin-bottom: 10px; color: #181818; letter-spacing: 1px;font-family:tahoma">'.$rowData->username.'</h2>
                <p style="padding:0; margin:0 0; color:#181818; font-size:1rem; font-weight: 300; text-transform: uppercase;font-family:tahoma">Validity</p>
                <h4 style="margin: 0px 0; color: #181818; font-size: 1.52rem;font-weight: 300;font-family:tahoma">'.date('d/m/Y',strtotime($rowData->validity)).'</h4>
            </div>

        </div><br><br>

        <div style="background: url(https://luxiday.com/admin/storage/pdf/platinum_member.png) no-repeat 0 0; background-size:100%; padding:24px 20px 30px 20px; width: 420px; min-height: 238px;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <h3 style="color: #181818; font-size: 1.5rem; font-weight: 300; letter-spacing: 1px; margin: 0;font-family:tahoma">'.$rowData->hotel_name.'</h3>
                <div style="display: inline-flex;padding-left:350px;"><img src="https://luxiday.com/admin/public/admin/images/teams/'.$rowData->image.'" style="width: 60px; height: 60px;"/></div>
            </div>
            <div style="padding: 0px 0 48px 0;">
                <h2 style="font-size: 1.52rem; font-weight: 400; margin-top: 10px; margin-bottom: 10px; color: #181818; letter-spacing: 1px;font-family:tahoma">'.$rowData->username.'</h2>
                <p style="padding:0; margin:0 0; color:#181818; font-size:1rem; font-weight: 300; text-transform: uppercase;font-family:tahoma">Validity</p>
                <h4 style="margin: 0px 0; color: #181818; font-size: 1.52rem;font-weight: 300;font-family:tahoma">'.date('d/m/Y',strtotime($rowData->validity)).'</h4>
            </div>

        </div>

    </div>';
		$fileName = 'card-'.$request->id.'.pdf';
		$mypdf = new mPDF([
			'margin_left' => 5,
			'margin_right' => 5,
			'margin_top' => 5,
			'margin_bottom' => 5,
			'margin_header' => 1,
			'margin_footer' => 1,
		]);
		$mypdf->SetDisplayMode('fullpage');
		$mypdf->WriteHTML($html);
		$storage_path = storage_path();
		$structure = $storage_path . "/pdf/";
		$file_name = $structure . $fileName;
		$mypdf->Output($file_name);
		echo $file_name; die;
        die;
    }
    #admin dashboard page
    public function getList(Request $request){

        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }
        return view('/admin/memberships_request/index');
    }

    public function listPaginate(Request $request){

        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }

        $query = self::$MembershipRequests->where('status', '!=', 3);
        if ($request->input('title') && $request->input('title') != ""){
            $title = $request->input('title');
            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);
        return view('/admin/memberships_request/paginate', compact('records'));

    }

    #add new Service Type

    public function addPage(Request $request){

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        if ($request->input()){

            $validator = Validator::make($request->all(), [

				'hotel_name' => 'required', 

				'username' => 'required'

			], [

				'hotel_name.required' => 'Please enter hotel_name.', 

				'username.required' => 'Please enter username.'

			]);

            if ($validator->fails()){

                $errors = $validator->errors();

                if ($errors->first('hotel_name')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('hotel_name')));

                    die;

                }

                if ($errors->first('username')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('username')));

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
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true) . mt_rand(100001, 999999)) . uniqid(mt_rand() . true) . $request->file('image')) . '.' . $request->image->extension());
                        $destination = base_path() . '/public/admin/images/teams/';
                        $request->image->move($destination, $actual_image_name);
                        $setData['image'] = $actual_image_name;
                    }
                }


                $setData['hotel_name'] = $request->input('hotel_name');
                $setData['username'] = $request->input('username');
				$setData['phone'] = $request->input('phone');
				$setData['validity'] = $request->input('validity');

                $record = self::$MembershipRequests->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Request added successfully'));

                die;

            }

        }

        return view('/admin/memberships_request/add-page');

    }

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);
        if (!$request->session()->has('admin_email')){
            return redirect('/admin/');
        }
        $rowData = self::$MembershipRequests->where(array('id' => $RowID))->first();

        if ($request->input()){

            $validator = Validator::make($request->all(), [
				'hotel_name' => 'required', 
				'username' => 'required'
			], [
				'hotel_name.required' => 'Please enter hotel_name.', 
				'username.required' => 'Please enter username.'
			]);

            if ($validator->fails()){
                $errors = $validator->errors();
                if ($errors->first('hotel_name')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('hotel_name')));
                    die;
                }
                if ($errors->first('username')){
                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('username')));
                    die;
                }

            } else {
				
				if(isset($request->image) && $request->image->extension() != ""){
                    $validator = Validator::make($request->all(), ['image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);
                    if ($validator->fails()){
                        $errors = $validator->errors();
                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));
                        die;
                    } else {
                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true) . mt_rand(100001, 999999)) . uniqid(mt_rand() . true) . $request->file('image')) . '.' . $request->image->extension());
                        $destination = base_path() . '/public/admin/images/teams/';
                        $request->image->move($destination, $actual_image_name);
                        $setData['image'] = $actual_image_name;
                        if ($rowData->image != ""){
                            if (file_exists($destination . $rowData->image)){
                                unlink($destination . $rowData->image);
                            }
                        }
                    }
                }
				
                $setData['id'] = $RowID;
                $setData['hotel_name'] = $request->input('hotel_name');
                $setData['username'] = $request->input('username');
				$setData['phone'] = $request->input('phone');
				$setData['validity'] = $request->input('validity');

                self::$MembershipRequests->UpdateRecord($setData);

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Request updated successfully'));

            die;

        }

        if (isset($rowData->id)){

            return view('/admin/memberships_request/edit-page', compact('rowData', 'row_id'));

        } else {

            return redirect('/admin/membership-request');

        }

    }

	

}