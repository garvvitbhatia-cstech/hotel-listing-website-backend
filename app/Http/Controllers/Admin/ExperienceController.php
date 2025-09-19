<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Experiences;

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


class ExperienceController extends Controller {
	
    private static $Experience;

    private static $TokenHelper;

    public function __construct(){

        self::$Experience = new Experiences();

        self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/experience/index');

    }

    public function listPaginate(Request $request){

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$Experience->where('status', '!=', 3);

        if ($request->input('title') && $request->input('title') != ""){

            $title = $request->input('title');

            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/experience/paginate', compact('records'));

    }

    #add new Service Type

    public function addPage(Request $request){

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        if ($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required', 

				'description' => 'required'

			], [

				'title.required' => 'Please enter title.', 

				'title.unique' => 'Title already exists.', 

				'description.required' => 'Please enter description.'

			]);

            if ($validator->fails()){

                $errors = $validator->errors();

                if ($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

                if ($errors->first('description')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('description')));

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

                $setData['title'] = $request->input('title');

                $setData['slug'] = Str::slug($request->input('title'));

                $setData['description'] = $request->input('description');

                $record = self::$Experience->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Experience added successfully'));

                die;

            }

        }

        return view('/admin/experience/add-page');

    }

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if (!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$Experience->where(array('id' => $RowID))->first();

        if ($request->input()){

            $validator = Validator::make($request->all(), [

				'title' => 'required', 

				'description' => 'required'

			], [

				'title.required' => 'Please enter title.', 

				'title.unique' => 'Title already exists.', 

				'description.required' => 'Please enter description.'

			]);

            if ($validator->fails()){

                $errors = $validator->errors();

                if ($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

                if ($errors->first('description')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('description')));

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

                        if ($rowData->image != ""){

                            if (file_exists($destination . $rowData->image)){

                                unlink($destination . $rowData->image);

                            }

                        }

                    }

                }

                $setData['id'] = $RowID;

                $setData['title'] = $request->input('title');

                $setData['slug'] = Str::slug($request->input('title'));

                $setData['description'] = $request->input('description');

                self::$Experience->UpdateRecord($setData);

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Experience updated successfully'));

            die;

        }

        if (isset($rowData->id)){

            return view('/admin/experience/edit-page', compact('rowData', 'row_id'));

        } else {

            return redirect('/admin/experience');

        }

    }

	

}