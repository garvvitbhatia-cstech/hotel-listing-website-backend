<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Categories;

use App\Models\SubCategories;

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


class SubCategoriesController extends Controller {
	
    private static $Categories;

    private static $subCategories;

    private static $TokenHelper;

    public function __construct(){

        self::$Categories = new Categories();

        self::$subCategories = new subCategories();

		self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/sub_categories/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$subCategories->where('status', '!=', 3);

        if($request->input('title') && $request->input('title') != ""){

            $title = $request->input('title');

            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/sub_categories/paginate', compact('records'));

    }

    #add new Service Type

    public function addPage(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'category_id' => 'required', 

                'title' => 'required', 

			], [

				'category_id.required' => 'Please select category.',

                'title.required' => 'Please enter title.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('category_id')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('category_id')));

                    die;

                }

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            } else {

				//if(!self::$subCategories->ExistingRecord($request->input('title'))){

                    $category = $this->getCategory($request->input('category_id'));

        	        $setData['category_id'] = $request->input('category_id');

                    $setData['title'] = $request->input('title');

					$setData['slug'] = Str::slug($request->input('title').' '.$category->title);

    	            $setData['description'] = $request->input('description');

                    $setData['seo_title'] = $request->input('seo_title');

                    $setData['seo_description'] = $request->input('seo_description');

                    $setData['seo_keyword'] = $request->input('seo_keyword');

                    $setData['robot_tags'] = $request->input('robot_tags');

	                $record = self::$subCategories->CreateRecord($setData);

					echo json_encode(array('heading' => 'Success', 'msg' => 'Category added successfully'));die;

				//}else{

					//echo json_encode(array('heading' => 'Error', 'msg' => 'Category details already exists.'));die;

				//}				

            }

        }

        $categories = $this->getCategoryList();
        return view('/admin/sub_categories/add-page',compact('categories'));

    }

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$subCategories->where(array('id' => $RowID))->first();

        if($request->input()){

            $validator = Validator::make($request->all(), [

				'category_id' => 'required', 

                'title' => 'required', 

			], [

				'category_id.required' => 'Please select category.',

                'title.required' => 'Please enter title.'

			]);

            if($validator->fails()){

                $errors = $validator->errors();

                if($errors->first('category_id')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('category_id')));

                    die;

                }

                if($errors->first('title')){

                    return json_encode(array('heading' => 'Error', 'msg' => $errors->first('title')));

                    die;

                }

            } else {

				//if(self::$Categories->ExistingRecordUpdate($request->input('title'), $RowID)){

                    //echo json_encode(array('heading'=>'Error','msg'=>'Category details already exists.'));die;

                //}else{
                    $category = $this->getCategory($request->input('category_id'));

        	        $setData['id'] = $RowID;

    	            $setData['category_id'] = $request->input('category_id');

                    $setData['title'] = $request->input('title');

					$setData['slug'] = Str::slug($request->input('title').' '.$category->title);

	                $setData['description'] = $request->input('description');

                    $setData['seo_title'] = $request->input('seo_title');

                    $setData['seo_description'] = $request->input('seo_description');

                    $setData['seo_keyword'] = $request->input('seo_keyword');

                    $setData['robot_tags'] = $request->input('robot_tags');

                	self::$subCategories->UpdateRecord($setData);

				//}

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Category updated successfully'));

            die;

        }

        if(isset($rowData->id)){

            $categories = $this->getCategoryList();

            return view('/admin/sub_categories/edit-page', compact('rowData', 'row_id','categories'));

        } else {

            return redirect('/admin/sub-categories');

        }

    }

    public function getCategoryList(){
        return self::$Categories->where('status', '!=', 3)->pluck('title','id');
    }

    public function getCategory($root_id){
        return self::$Categories->where('status', '!=', 3)->where('id',$root_id)->first();
    }


}