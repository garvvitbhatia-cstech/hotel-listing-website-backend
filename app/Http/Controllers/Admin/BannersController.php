<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Banners;

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



class BannersController extends Controller{



    private static $Banners;

    private static $TokenHelper;

    public function __construct(){

        self::$Banners = new Banners();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/banners/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$Banners->where('status', '!=', 3);

        if($request->input('status') && $request->input('status') != ""){

            $status = $request->input('status');

            $query->where('status', 'like', '%'.$status.'%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/banners/paginate', compact('records'));

    }

	
    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'banner' => 'required', 

			], [

				'banner.required' => 'Please enter banner.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('banner')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));

                    die;

                }

            }else{

                if(isset($request->banner) && $request->banner->extension() != ""){

                    $validator = Validator::make($request->all(), [

						'banner' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

					]);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));

                        die;

                    }else{

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('banner')).'.'.$request->banner->extension());

                        $destination = base_path().'/public/admin/images/teams/';

                        $request->banner->move($destination, $actual_image_name);

                        $setData['banner'] = $actual_image_name;

                    }

                }

                $setData['heading'] = $request->input('heading');

                $setData['text'] = $request->input('text');

                $record = self::$Banners->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Banner added successfully'));

                die;

            }

        }

        return view('/admin/banners/add-page');

    }

	

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$Banners->where(array('id' => $RowID))->first();

        if($request->input()){

            if(isset($request->banner) && $request->banner->extension() != ""){

                $validator = Validator::make($request->all(), [

                    'banner' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

                ]);

                if($validator->fails()){

                    $errors = $validator->errors();

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('banner')));

                    die;

                }else{

                    $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('banner')).'.'.$request->banner->extension());

                    $destination = base_path().'/public/admin/images/teams/';

                    $request->banner->move($destination, $actual_image_name);

                    $setData['banner'] = $actual_image_name;

                    if($rowData->banner != ""){

                        if(file_exists($destination.$rowData->banner)){

                            unlink($destination.$rowData->banner);

                        }

                    }

                }

            }

            $setData['id'] = $RowID;

            $setData['heading'] = $request->input('heading');

            $setData['text'] = $request->input('text');

            self::$Banners->UpdateRecord($setData);        

            echo json_encode(array('heading' => 'Success', 'msg' => 'Banner updated successfully'));

            die;

        }

        if(isset($rowData->id)){

            return view('/admin/banners/edit-page', compact('rowData', 'row_id'));

        }else{

            return redirect('/admin/Banners');

        }

    }

	

}