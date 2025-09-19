<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\RouteHelper;

use App\Models\TokenHelper;

use App\Models\Responses;

use ReallySimpleJWT\Token;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Session;

use Validator;

use Mail;

use URL;

use Cookie;

use Illuminate\Validation\Rule;


use App\Models\State;


class AjaxController extends Controller{

    private static $TokenHelper;

	public function __construct(){

        self::$TokenHelper = new TokenHelper();

	}
	

	public function changePrice(Request $request){

		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}

		$value = $request->input('value');

		$rowID = $request->input('id');

		$field = $request->input('field');

		if(DB::table('products')->where(array('id' => $rowID))->update(array($field => $value))){

			echo "Success"; die;

		}else{

			echo "Error"; die;

		}

	}
	

	public function changeProductStatus(Request $request){

		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}

		$tableName = $request->input('table');

		$rowID = $request->input('rowID');

		$status = $request->input('status');

		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){            

            $newStatus = $status == 1 ? 2 : 1;

			DB::table($tableName)->where(array('id' => $rowID))->update(array('featured' => $newStatus));

			echo 'Success';die;

		}else{

			echo 'InvalidData'; die;

		}

    }


    public function changeStatus(Request $request){

		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}

		$tableName = $request->input('table');

		$rowID = $request->input('rowID');

		$status = $request->input('status');

		if($tableName != "" && $rowID != "" && $status != "" && is_numeric($rowID) && is_numeric($status)){            

            $newStatus = $status == 1 ? 2 : 1;

			DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => $newStatus));

			echo 'Success';die;

		}else{

			echo 'InvalidData'; die;

		}

    }


    public function deleteRecord(Request $request){

		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}

		$tableName = $request->input('table');

		$rowID = $request->input('rowID');

		if($tableName != "" && $rowID != "" && is_numeric($rowID)){            

            DB::table($tableName)->where(array('id' => $rowID))->update(array('status' => 3));

			echo 'Success';die;

		}else{

			echo 'InvalidData'; die;

		}

    }


    public function productsChangeStatus(Request $request){

		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}

		$productIDs = $request->input('productIDs');

		$status = $request->input('status');

        if(count($productIDs) == 0){

            echo 'Please select Products.';die;

        }

		if($status != "" && is_numeric($status)){

            foreach($productIDs as $rowID){

                $newStatus = $status == 1 ? 2 : 1;

                DB::table('products')->where(array('id' => $rowID))->update(array('status' => $newStatus));

            }

			echo 'Success';die;

		}else{

			echo 'InvalidData'; die;

		}

    }


    public function productsDeleteRecord(Request $request){

		if(!$request->session()->has('admin_email')){echo 'SessionExpire'; die;}

		$productIDs = $request->input('productIDs');

		$status = $request->input('status');

        if(count($productIDs) == 0){

            echo 'Please select Products.';die;

        }

        foreach($productIDs as $rowID){

            $newStatus = $status == 1 ? 2 : 1;

            //DB::table('products')->where('id', $rowID)->delete();

            DB::table('products')->where(array('id' => $rowID))->update(array('status' => 3));

        }

        echo 'Success';die;

    }
	

	public function getState(Request $request){

		if($request->ajax()){

			$country_id = $request->input('countryId');

			$states = DB::table('states')->where('country_id',$country_id)->where('status',1)->orderBy('state')->pluck('state','id');

			echo view('/admin/ajax/get_state',compact('states'));

		}

		exit;

	}
	

	public function getCity(Request $request){

		if($request->ajax()){

			$state_id = $request->input('stateId');

			$cities = DB::table('cities')->where('state_id',$state_id)->where('status',1)->orderBy('city')->pluck('city','id');

			echo view('/admin/ajax/get_city',compact('cities'));

		}

		exit;

	}

	public function getSubCategories(Request $request){

		if($request->ajax()){

			$category_id = $request->input('category_id');

			$categories = DB::table('sub_categories')->where('category_id',$category_id)->where('status',1)->orderBy('title')->pluck('title','id');

			echo view('/admin/ajax/get_sub_categories',compact('categories'));

		}

		exit;

	}

	public function getEmployeeList(Request $request){
        if($request->ajax()){
            $postData = $request->all();
            $json = array();
            if(isset($postData['q'])){
                $term = $postData['q'];
                if($term != ''){
                    $users = DB::table('users')->where('type', 'Staff')->where('name', 'like', '%' . $term . '%')->where('status', 1)->limit('20')->get();
                    foreach ($users as $key => $user){
                        $json[] = ['id' => $user->id, 'title' => $user->name . ' (' . $user->emp_id . ') - ₹' . $user->salary];
                    }
                }
            }
            echo json_encode($json);
        }
        exit;
    }


}