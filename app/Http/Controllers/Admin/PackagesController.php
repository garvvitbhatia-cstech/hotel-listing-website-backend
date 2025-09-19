<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Packages;

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



class PackagesController extends Controller{

    private static $Packages;

    private static $TokenHelper;

    public function __construct(){

        self::$Packages = new Packages();

		self::$TokenHelper = new TokenHelper();

    }

    #admin dashboard page

    public function getList(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        return view('/admin/packages/index');

    }

    public function listPaginate(Request $request){

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $query = self::$Packages->where('status', '!=', 3);

        if($request->input('title') && $request->input('title') != ""){

            $title = $request->input('title');

            $query->where('title', 'like', '%' . $title . '%');

        }

        $records = $query->orderBy('id', 'DESC')->simplePaginate(20);

        return view('/admin/packages/paginate', compact('records'));

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

                $setData['title'] = $request->input('title');

                $setData['description'] = $request->input('description');

                $setData['price'] = $request->input('price');

                $record = self::$Packages->CreateRecord($setData);

                echo json_encode(array('heading' => 'Success', 'msg' => 'Package added successfully'));

                die;

            }

        }

        return view('/admin/packages/add-page');

    }

    #edit Service Type

    public function editPage(Request $request, $row_id){

        $RowID = base64_decode($row_id);

        if(!$request->session()->has('admin_email')){

            return redirect('/admin/');

        }

        $rowData = self::$Packages->where(array('id' => $RowID))->first();

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

                $setData['id'] = $RowID;

                $setData['title'] = $request->input('title');

                $setData['description'] = $request->input('description');

                $setData['price'] = $request->input('price');

                self::$Packages->UpdateRecord($setData);

            }

            echo json_encode(array('heading' => 'Success', 'msg' => 'Package updated successfully'));

            die;

        }

        if(isset($rowData->id)){

            return view('/admin/packages/edit-page', compact('rowData', 'row_id'));

        } else {

            return redirect('/admin/packages');

        }

    }


}