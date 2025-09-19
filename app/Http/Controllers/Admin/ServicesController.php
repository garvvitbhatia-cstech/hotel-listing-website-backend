<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Services;

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



class ServicesController extends Controller{

	

    private static $Services;

    private static $TokenHelper;

    public function __construct(){

        self::$Services = new Services();

		self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/services/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$Services->where('status', '!=', 3);

        if($request->input('title') && $request->input('title') != ""){

            $title = $request->input('title');

            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/services/paginate', compact('records'));

    }

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required', 

			], [

				'title.required' => 'Please enter title.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            } else {

                if(isset($request->image) && $request->image->extension() != ""){

                    $validator = Validator::make($request->all(), ['image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));

                        die;

                    } else {

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('image')).'.' . $request->image->extension());

                        $destination = base_path() . '/public/admin/images/products/';

                        $request->image->move($destination, $actual_image_name);

                        $setData['image'] = $actual_image_name;

                    }

                }

				if(isset($request->icon) && $request->icon->extension() != ""){

                    $validator = Validator::make($request->all(), ['icon' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('icon')));

                        die;

                    } else {

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('icon')).'.' . $request->icon->extension());

                        $destination = base_path() . '/public/admin/images/products/';

                        $request->icon->move($destination, $actual_image_name);

                        $setData['icon'] = $actual_image_name;

                    }

                }

                $setData['title'] = $request->input('title');

                $setData['description'] = $request->input('description');

                $record = self::$Services->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Service added successfully'));

                die;

            }

        }

        return view('/admin/services/add-page');

    }

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$Services->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required', 

			], [

				'title.required' => 'Please enter title.',

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            } else {

                if(isset($request->image) && $request->image->extension() != ""){

                    $validator = Validator::make($request->all(), ['image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));

                        die;

                    } else {

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('image')).'.' . $request->image->extension());

                        $destination = base_path() . '/public/admin/images/products/';

                        $request->image->move($destination, $actual_image_name);

                        $setData['image'] = $actual_image_name;

                        if($rowData->image != ""){

                            if(file_exists($destination . $rowData->image)){

                                unlink($destination . $rowData->image);

                            }

                        }

                    }

                }

				if(isset($request->icon) && $request->icon->extension() != ""){

                    $validator = Validator::make($request->all(), ['icon' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480']);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('icon')));

                        die;

                    } else {

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('icon')).'.' . $request->icon->extension());

                        $destination = base_path() . '/public/admin/images/products/';

                        $request->icon->move($destination, $actual_image_name);

                        $setData['icon'] = $actual_image_name;

                        if($rowData->icon != ""){

                            if(file_exists($destination . $rowData->icon)){

                                unlink($destination . $rowData->icon);

                            }

                        }

                    }

                }

                $setData['id'] = $RowID;

                $setData['title'] = $request->input('title');

                $setData['description'] = $request->input('description');

                self::$Services->UpdateRecord($setData);

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Service updated successfully'));

            die;

        }

        if(isset($rowData->id)){

            return view('/admin/services/edit-page', compact('rowData', 'row_id'));

        } else {

            return redirect('/admin/services');

        }

    }



}