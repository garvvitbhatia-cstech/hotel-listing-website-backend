<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Blogs;

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



class BlogsController extends Controller{



    private static $Blogs;

    private static $TokenHelper;

    public function __construct(){

        self::$Blogs = new Blogs();

        self::$TokenHelper = new TokenHelper();

    }

	

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){ 

            return redirect('/admin/');

        }

        return view('/admin/blogs/index');

    }

	

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$Blogs->where('status', '!=', 3);

        if($request->input('title') && $request->input('title') != ""){

            $title = $request->input('title');

            $query->where('title', 'like', '%'.$title.'%');

        }
		
		if($request->input('type') && $request->input('type') != ""){

            $type = $request->input('type');

            $query->where('type', 'like', '%'.$type.'%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/blogs/paginate', compact('records'));

    }

	

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required|unique:blogs', 

			], [

				'title.required' => 'Please enter title.', 

				'title.unique' => 'Title already exists.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            }else{

                if(isset($request->image) && $request->image->extension() != ""){

                    $validator = Validator::make($request->all(), [

						'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

					]);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));

                        die;

                    }else{

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('image')).'.'.$request->image->extension());

                        $destination = base_path().'/public/admin/images/teams/';

                        $request->image->move($destination, $actual_image_name);

                        $setData['image'] = $actual_image_name;

                    }

                }

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

                        if($request->input('old_profile_image') != ""){

                            if(file_exists($destination.$request->input('old_profile_image'))){

                                unlink($destination.$request->input('old_profile_image'));

                            }

                        }

                    }

                }

                $setData['title'] = $request->input('title');
				$setData['type'] = $request->input('type');

                $setData['slug'] = Str::slug($request->input('title'));

                $setData['description'] = $request->input('description');

                $setData['seo_title'] = $request->input('seo_title');

                $setData['seo_description'] = $request->input('seo_description');

                $setData['seo_keyword'] = $request->input('seo_keyword');

                $setData['robot_tags'] = $request->input('robot_tags');

                $record = self::$Blogs->CreateRecord($setData);



                echo json_encode(array('heading' => 'Success', 'msg' => 'Blog added successfully'));

                die;

            }

        }

        return view('/admin/blogs/add-page');

    }

	

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$Blogs->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required|unique:blogs,title,'.$RowID, 

			], [

				'title.required' => 'Please enter title.', 

				'title.unique' => 'Title already exists.', 

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            }else{

                if(isset($request->image) && $request->image->extension() != ""){

                    $validator = Validator::make($request->all(), [

						'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

					]);

                    if($validator->fails()){

                        $errors = $validator->errors();

                        return json_encode(array('heading' => 'Error', 'msg' => $errors->first('image')));

                        die;

                    }else{

                        $actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001, 999999)).uniqid(mt_rand().true).$request->file('image')).'.'.$request->image->extension());

                        $destination = base_path().'/public/admin/images/teams/';

                        $request->image->move($destination, $actual_image_name);

                        $setData['image'] = $actual_image_name;

                        if($rowData->image != ""){

                            if(file_exists($destination.$rowData->image)){

                                unlink($destination.$rowData->image);

                            }

                        }

                    }

                }

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

                $setData['type'] = $request->input('type');
				$setData['title'] = $request->input('title');

                $setData['slug'] = Str::slug($request->input('title'));

                $setData['description'] = $request->input('description');

                $setData['seo_title'] = $request->input('seo_title');

                $setData['seo_description'] = $request->input('seo_description');

                $setData['seo_keyword'] = $request->input('seo_keyword');

                $setData['robot_tags'] = $request->input('robot_tags');

                self::$Blogs->UpdateRecord($setData);

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Blog updated successfully'));

            die;

        }

        if(isset($rowData->id)){

            return view('/admin/blogs/edit-page', compact('rowData', 'row_id'));

        }else{

            return redirect('/admin/blogs');

        }

    }

	

}