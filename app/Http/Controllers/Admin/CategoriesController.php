<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Categories;

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



class CategoriesController extends Controller{

	

    private static $Categories;

    private static $TokenHelper;

    public function __construct(){

        self::$Categories = new Categories();

		self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/categories/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$Categories->where('status', '!=', 3);

        if($request->input('title') && $request->input('title') != ""){

            $title = $request->input('title');

            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/categories/paginate', compact('records'));

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

				if(!self::$Categories->ExistingRecord($request->input('title'))){

        	        $setData['title'] = $request->input('title');

					$setData['slug'] = Str::slug($request->input('title'));

    	            $setData['description'] = $request->input('description');

                    $setData['seo_title'] = $request->input('seo_title');

                    $setData['seo_description'] = $request->input('seo_description');

                    $setData['seo_keyword'] = $request->input('seo_keyword');

                    $setData['robot_tags'] = $request->input('robot_tags');

	                $record = self::$Categories->CreateRecord($setData);

					echo json_encode(array('heading' => 'Success', 'msg' => 'Category added successfully'));die;

				}else{

					echo json_encode(array('heading' => 'Error', 'msg' => 'Category details already exists.'));die;

				}

				

            }

        }

        return view('/admin/categories/add-page');

    }

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$Categories->where(array('id' => $RowID))->first();

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

				if(self::$Categories->ExistingRecordUpdate($request->input('title'), $RowID)){

                    echo json_encode(array('heading'=>'Error','msg'=>'Category details already exists.'));die;

                }else{

        	        $setData['id'] = $RowID;

    	            $setData['title'] = $request->input('title');

					$setData['slug'] = Str::slug($request->input('title'));

	                $setData['description'] = $request->input('description');

                    $setData['seo_title'] = $request->input('seo_title');

                    $setData['seo_description'] = $request->input('seo_description');

                    $setData['seo_keyword'] = $request->input('seo_keyword');

                    $setData['robot_tags'] = $request->input('robot_tags');

                	self::$Categories->UpdateRecord($setData);

				}

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Category updated successfully'));

            die;

        }

        if(isset($rowData->id)){

            return view('/admin/categories/edit-page', compact('rowData', 'row_id'));

        } else {

            return redirect('/admin/categories');

        }

    }



}