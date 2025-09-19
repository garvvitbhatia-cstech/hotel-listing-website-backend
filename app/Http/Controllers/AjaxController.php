<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\RouteHelper;

use App\Models\TokenHelper;

use App\Models\Enquiries;

use App\Models\Settings;

use App\Models\SeasonMonths;

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

use App\Mail\NewsletterMail;

use App\Mail\ContactMail;



class AjaxController extends Controller{



    private static $TokenHelper;

	private static $Enquiries;

	private static $Settings;

	

	public function __construct(){

        self::$TokenHelper = new TokenHelper();

		self::$Enquiries = new Enquiries();

		self::$Settings = new Settings();

	}

	

	public function addNewsletter(Request $request){

		if($request->ajax()){	

			if(!empty($request->email) && filter_var($request->email, FILTER_VALIDATE_EMAIL)){

				################Send Email###########################

				$settingData = self::$Settings->where('id',1)->first();

				$data = [

					'email' => strtolower($request->email)

				];

				//Mail::to([strtolower(trim($settingData->admin_email))])->send(new NewsletterMail($data));

				$this->newsletter($request->email,$request->name);

			}

		}

		exit;

	}

	

	public function addEnquiry(Request $request){

		if($request->ajax()){			

			$postData = $request->all();			

			$msg = '';

			if(isset($postData) && !empty($postData)){

				$validator = Validator::make($request->all(),[

					'cfname' => 'required|regex:/^[\p{L}\p{M}\s.\-]+$/u',

					'ccontact' => 'required|digits:10',	

					'cemail' => 'required|email',

					'cmessage' => 'required',			

				],[

					'cfname.required' => 'Please enter name.',

					'cfname.regex' => 'Please enter valid name.',

					'ccontact.required' => 'Please enter contact number.',

					'ccontact.digits' => 'The contact must be 10 digits.',

					'cemail.required' => 'Please enter email.',

					'cemail.email' => 'Please enter valid email.',

					'cmessage.required' => 'Please enter message.',

				]);

				

				if($validator->passes()){					

					$is_valid = 1;

					if($is_valid == 1){

						try {

							$this->newsletter($request->cemail,$request->cfname);

							$setData['name'] = strip_tags($request->cfname); 

							if($request->product_id && $request->product_id != ''){

								$setData['product_id'] = strip_tags($request->product_id);

								$setData['type'] = 'Product';

							}

							$setData['name'] = strip_tags($request->cfname); 

							$setData['contact'] = strip_tags($request->ccontact); 

							$setData['email'] = strip_tags($request->cemail);

							$setData['message'] = strip_tags($request->cmessage);

							self::$Enquiries->CreateRecord($setData);

							

							################Send Email###########################

							$settingData = self::$Settings->where('id',1)->first();

							$data = [

								'name' => ucwords($request->cfname),

								'contact' => strtolower($request->ccontact),

								'email' => strtolower($request->cemail),

								'message' => $request->cmessage,

							];

							//Mail::to([strtolower(trim($settingData->admin_email))])->send(new ContactMail($data));

							

							return response()->json(['status'=>'success', 'msg' => 'Inquiry send successfully']);



					}

						catch(\Exception $e){

							return response()->json(['status' => 'error', 'msg' => 'Something went wrong.']);

						}		

					}			

				}else{

					return response()->json(['status' => 'error', 'msg' => 'error',  'errors'=>$validator->errors()->getMessages()]);	

				}					

			}

			exit;

		}

	}		



}