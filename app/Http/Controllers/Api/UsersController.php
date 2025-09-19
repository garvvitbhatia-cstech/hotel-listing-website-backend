<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Blogs;
use App\Models\Settings;
use App\Models\InnerPages;
use App\Models\Products;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Newsletter;
use App\Models\Responses;
use App\Models\Experts;
use App\Models\MainServices;
use App\Models\Enquiries;
use App\Models\Testimonials;
use App\Models\Faqs;
use App\Models\User;
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

class UsersController extends Controller
{
	private static $Blogs;
	private static $InnerPages;
	private static $Products;
	private static $Enquiries;
	private static $User;
	
	public function __construct(){
		self::$Blogs = new Blogs();
		self::$InnerPages = new InnerPages();
		self::$Products = new Products();
		self::$Enquiries = new Enquiries();
		self::$User = new User();
	}

	#register
    public function register(Request $request){

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required|numeric',
			'password' => 'required',
		],[
			'name.required' => 'Please enter name.',
			'email.required' => 'Please enter email.',
			'email.email' => 'Please enter valid email.',
			'phone.required' => 'Please enter phone.',
			'password.required' => 'Please enter password.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('name')){
				return response()->json(['success'=>false, 'message' => $errors->first('name')]);
			}
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
			if($errors->first('phone')){
				return response()->json(['success'=>false, 'message' => $errors->first('phone')]);
			}
			if($errors->first('password')){
				return response()->json(['success'=>false, 'message' => $errors->first('password')]);
			}
		}else{
			
			$phoneCheck = self::$User->where('mobile',$request->phone)->count();
			if($phoneCheck > 0){
				return response()->json(['success'=>false, 'message' => 'Mobile no already exist']);
			}
			$emailCheck = self::$User->where('email',$request->email)->count();
			if($emailCheck > 0){
				return response()->json(['success'=>false, 'message' => 'Email address already exist']);
			}
			
			$setData['name'] = $request->name;
			$setData['email'] = $request->email;
			$setData['mobile'] = $request->phone;
			$setData['password'] = $request->password;
			$setData['status'] = 1;
			$setData['type'] = $request->type;
			
			$record = self::$User->CreateRecord($setData);
			
			return response()->json(['success'=>true, 'message'=> $request->type.' registration successfully.'],200);
		}
    }
	#login
    public function login(Request $request){

		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
		],[
			'email.required' => 'Please enter email.',
			'password.required' => 'Please enter password.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
			if($errors->first('password')){
				return response()->json(['success'=>false, 'message' => $errors->first('password')]);
			}
		}else{
			$userData = self::$User->where('type','User')->where('email',$request->post('email'))->orWhere('mobile',$request->post('email'))->where('password',$request->password)->first();
			
			if(isset($userData->id)){
				if($userData->status == 2){
					return response()->json(['success'=>false, 'message' => 'Account is inactive']);
				}
				return response()->json(['success'=>true, 'message'=> 'Login successfully.'],200);
			}else{
				return response()->json(['success'=>false, 'message' => 'Invalid login details']);
			}
			
		}
    }
}
