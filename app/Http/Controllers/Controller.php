<?php
namespace App\Http\Controllers;

use App\Models\Newsletter;

use App\Models\User;

use App\Models\EmployeeAttendence;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Routing\Controller as BaseController;



class Controller extends BaseController{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;	

	public function newsletter($email = NULL, $name = NULL){

		if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){

			$email = strtolower(trim($email));

			$emailExist = Newsletter::where('email',$email)->count();

            if($emailExist == 0){

				$newsletter = new Newsletter();

                $newsletter->email = $email;

				$newsletter->name = $name;

				$newsletter->save();

            }

		}

		return "success";

	}



	public function builtSlug($input_lines){

        preg_match_all("/[0-9A-Za-z\s]/", trim($input_lines), $output_array);

        $slug = strtolower(preg_replace("/[\s]/", "-", join($output_array[0])));

        return preg_replace("/-{2,}/", "-", $slug);

    }

	function reverse($number){  

		/* writes number into string. */  

		$num = (string)$number;  

		/* Reverse the string. */  

		$revstr = strrev($num);  

		/* writes string into int. */  

		$reverse = (int)$revstr;   

		return $reverse;  

	}

	public function productCode($productName,$productID=NULL){

		$productCode = '';

		preg_match_all("/[0-9A-Za-z\s]/", trim($productName), $output_array);

        $slug = strtolower(preg_replace("/[\s]/", " ", join($output_array[0])));

        $productName = preg_replace("/-{2,}/", " ", $slug);		

		$exp = explode(' ',$productName);		

		foreach($exp AS $val){

			$productCode .= strtoupper(substr($val,0,1));

		}

		$productID = $this->reverse($productID);

		return $productCode.$productID;

	}

	public function checkLogin($request){
        $emp_id = $request->session()->get('admin_id');
        $admin_login_time = $request->session()->get('admin_login_time');
        $date = date('d-m-Y');
        $userDetails = User::where('status', 1)->where('id', $emp_id)->where('status', 1)->first();
        if(isset($userDetails->id) && $userDetails->type == 'Staff'){
            $record = EmployeeAttendence::where('status', 1)->where('employee_id', $userDetails->id)->where('date', $date)->where('status', '!=', 3)->first();
            if(isset($record->id) && !empty($record->check_in)){
                $dateDiff = intval((time() - $admin_login_time) / 60);
                $hours = intval($dateDiff / 60);
                $minutes = $dateDiff % 60;
                if($minutes > 60){
                    $request->session()->flush();
                    return redirect('/admin/');
                }
            }
        }
    }

}