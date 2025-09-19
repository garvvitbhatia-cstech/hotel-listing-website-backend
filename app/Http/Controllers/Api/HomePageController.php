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
use App\Models\MembershipRecords;
use App\Models\Testimonials;
use App\Models\Packages;
use App\Models\ProductImages;
use App\Models\Country;
use App\Models\Faqs;
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

class HomePageController extends Controller
{
	private static $Blogs;
	private static $InnerPages;
	private static $Products;
	private static $Enquiries;
	private static $Country;
	private static $Packages;
	private static $ProductImages;
	private static $MembershipRecords;
	/*private static $Settings;
	
	private static $Newsletter;
	
	private static $Experts;
	private static $Faqs;
	
	private static $MainServices;*/
	private static $Testimonials;
	
	public function __construct(){
		self::$Blogs = new Blogs();
		self::$InnerPages = new InnerPages();
		self::$Products = new Products();
		self::$Enquiries = new Enquiries();
		self::$Country = new Country();
		self::$Packages = new Packages();
		self::$ProductImages = new ProductImages();
		self::$MembershipRecords = new MembershipRecords();
		/*self::$Settings = new Settings();
		
		self::$Newsletter = new Newsletter();
		
		self::$Experts = new Experts();
		self::$Faqs = new Faqs();
		
		self::$MainServices = new MainServices();*/
		self::$Testimonials = new Testimonials();
	}
	public function latestPropertyList(Request $request){
		$hotels = self::$Products->select('title','slug','banner','address','city','state')->where('status',1)->orderBy('id','DESC')->get()->take(5);
		foreach($hotels as $key => $hotel){
			$blogBanner = 'blog-no-img.jpg';
			if($hotel->banner != ""){
				$blogBanner = $hotel->banner;
			}
			$hotel->banner = env('APP_URL').'/public/img/products/'.$blogBanner;
		}
		return response()->json(['success'=>true, 'hotels'=> $hotels],200);
    }
	public function propertyList(Request $request){
		
		
		$query = self::$Products->select('title','slug','banner','address','city','state')->where('status',1)->where('category_id',$request->category_id);
		
		$query = $query->where('type',$request->type);
		
		if($request->type == 'Domestic'){
			
			if($request->d_location && $request->d_location != ''){
				$query = $query->where('location',$request->d_location);
			}
			if($request->d_terrain && $request->d_terrain != ''){
				$query = $query->where('terrain',$request->d_terrain);
			}
			
		}else{
			if($request->i_location && $request->i_location != ''){
				$query = $query->where('location',$request->i_location);
			}
			if($request->i_terrain && $request->i_terrain != ''){
				$query = $query->where('terrain',$request->i_terrain);
			}
		}
		
		$hotels = $query->orderBy('id','DESC')->paginate(12);
	
		foreach($hotels as $key => $hotel){
			$blogBanner = 'blog-no-img.jpg';
			if($hotel->banner != ""){
				$blogBanner = $hotel->banner;
			}
			$hotel->banner = env('APP_URL').'/public/img/products/'.$blogBanner;
		}
		return response()->json(['success'=>true, 'hotels'=> $hotels],200);
    }
	public function b2bList(Request $request){
		
		
		$query = self::$Products->select('title','slug','banner','address','city','state')->where('status',1)->where('is_b2b',1);
		
		$query = $query->where('type',$request->type);
		
		if($request->type == 'Domestic'){
			
			if($request->d_location && $request->d_location != ''){
				$query = $query->where('location',$request->d_location);
			}
			if($request->d_terrain && $request->d_terrain != ''){
				$query = $query->where('terrain',$request->d_terrain);
			}
			
		}else{
			if($request->i_location && $request->i_location != ''){
				$query = $query->where('location',$request->i_location);
			}
			if($request->i_terrain && $request->i_terrain != ''){
				$query = $query->where('terrain',$request->i_terrain);
			}
		}
		
		$hotels = $query->orderBy('id','DESC')->paginate(12);
	
		foreach($hotels as $key => $hotel){
			$blogBanner = 'blog-no-img.jpg';
			if($hotel->banner != ""){
				$blogBanner = $hotel->banner;
			}
			$hotel->banner = env('APP_URL').'/public/img/products/'.$blogBanner;
		}
		return response()->json(['success'=>true, 'hotels'=> $hotels],200);
    }
	public function countryList(Request $request){
		$countries = self::$Country->select('id','country_name')->where('status',1)->orderBy('country_name','ASC')->get();
		return response()->json(['success'=>true, 'countries'=> $countries],200);
    }
	public function packageList(Request $request){
		$packages = self::$Packages->where('status',1)->orderBy('id','ASC')->get();
		return response()->json(['success'=>true, 'packages'=> $packages],200);
    }
	public function testimonialsList(Request $request){
		
		$testimonials = self::$Testimonials->where('status',1)->orderBy('id','DESC')->get()->take(6);
		foreach($testimonials as $key => $testimonial){
			$usersPic = 'blog-no-img.jpg';
			if($testimonial->user_profile != ""){
				$usersPic = $testimonial->user_profile;
			}
			$testimonial->pic = env('APP_URL').'/public/admin/images/teams/'.$usersPic;
		}
		return response()->json(['success'=>true, 'testimonials'=> $testimonials],200);
    }
    public function blogList(Request $request){
		
		if($request->page == 'HomePage'){
			$blogs = self::$Blogs->where('status',1)->orderBy('id','DESC')->get()->take(2);
		}else{
			$blogs = self::$Blogs->where('status',1)->where('type',$request->type)->orderBy('id','DESC')->get()->take(6);
		}
		
		foreach($blogs as $key => $blog){
			$blogBanner = 'blog-no-img.jpg';
			if($blog->image != ""){
				$blogBanner = $blog->image;
			}
			$blog->blog_banner = env('APP_URL').'/public/admin/images/teams/'.$blogBanner;
			$blog->description = strlen($blog->description > 140) ? substr($blog->description,0,140).'...' : $blog->description;
			$blog->read_more = strlen($blog->description > 140) ? true : false;
			$blog->post_date = $blog->post_date != "" ? date('F d, Y',strtotime($blog->post_date)) : date('F d, Y',strtotime($blog->created_at));
		}
		return response()->json(['success'=>true, 'blogs'=> $blogs],200);
    }
	#propertyGet
    public function propertyGet(Request $request){

		$validator = Validator::make($request->all(), [
			'slug' => 'required',
		],[
			'slug.required' => 'Please enter slug.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('slug')){
				return response()->json(['success'=>false, 'message' => $errors->first('slug')]);
			}
		}else{
			$hotelDetailas = self::$Products->where('slug',$request->slug)->where('status',1)->first();
			$blogBanner = 'blog-no-img.jpg';
			if($hotelDetailas->banner != ""){
				$blogBanner = $hotelDetailas->banner;
			}
			$hotelDetailas->banner = env('APP_URL').'/public/img/products/'.$blogBanner;
			
			$hero_section_banner = 'hotel_details.png';
			if($hotelDetailas->hero_section_banner != ""){
				$hero_section_banner = $hotelDetailas->hero_section_banner;
			}
			$hotelDetailas->hero_section_banner = env('APP_URL').'/public/img/products/'.$hero_section_banner;
			
			$propertyImages = self::$ProductImages->select('image')->where('product_id',$hotelDetailas->id)->where('status',1)->get();
			foreach($propertyImages as $key => $propertyImage){
				$propertyImage->image = env('APP_URL').'/public/img/products/'.$propertyImage->image;
			}
			
			return response()->json(['success'=>true, 'blogData'=> $hotelDetailas,'propertyImages' => $propertyImages],200);
		}
    }
	#blogGet
    public function blogGet(Request $request){

		$validator = Validator::make($request->all(), [
			'slug' => 'required',
		],[
			'slug.required' => 'Please enter slug.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('slug')){
				return response()->json(['success'=>false, 'message' => $errors->first('slug')]);
			}
		}else{
			$blogDetailas = self::$Blogs->where('slug',$request->slug)->where('status',1)->first();
			$blogBanner = 'blog-no-img.jpg';
			if($blogDetailas->banner != ""){
				$blogBanner = $blogDetailas->banner;
			}
			$blogDetailas->banner = env('APP_URL').'public/admin/images/teams/'.$blogBanner;
			$blogDetailas->post_date = $blogDetailas->post_date != "" ? date('F d, Y',strtotime($blogDetailas->post_date)) : date('F d, Y',strtotime($blogDetailas->created_at));
			
			$blogs = self::$Blogs->select('title','slug','image','created_at')->where('status',1)->where('type',$blogDetailas->type)->orderBy('id','DESC')->get()->take(3);
			foreach($blogs as $key => $blog){
				$blogBanner = 'blog-no-img.jpg';
				if($blog->image != ""){
					$blogBanner = $blog->image;
				}
				$blog->blog_banner = env('APP_URL').'/public/admin/images/teams/'.$blogBanner;
				$blog->description = strlen($blog->description > 140) ? substr($blog->description,0,140).'...' : $blog->description;

				$blog->post_date = $blog->post_date != "" ? date('F d, Y',strtotime($blog->post_date)) : date('F d, Y',strtotime($blog->created_at));
			}
			
			return response()->json(['success'=>true, 'blogData'=> $blogDetailas, 'blogs'=> $blogs],200);
		}
    }
	#pageGet
    public function pageDataGet(Request $request){

		$validator = Validator::make($request->all(), [
			'id' => 'required',
		],[
			'id.required' => 'Please enter id.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('id')){
				return response()->json(['success'=>false, 'message' => $errors->first('id')]);
			}
		}else{
			$record = self::$InnerPages->where('id',$request->id)->where('status',1)->first();
			$record->banner = env('APP_URL').'public/admin/images/banners/'.$record->banner;
			return response()->json(['success'=>true, 'record'=> $record],200);
		}
    }
	public function popularSearch(Request $request){
		
		$projects = self::$Products->select('city')->where('status',1)->groupBy('city')->get();
		return response()->json(['success'=>true, 'cities'=> $projects],200);
    }
	public function faqList(Request $request){
		
		if($request->type == 'HomePage'){
			$projects = self::$Faqs->where('status',1)->orderBy('id','DESC')->get()->take(2);
		}else{
			$projects = self::$Faqs->where('status',1)->orderBy('id','DESC')->get();
		}
		return response()->json(['success'=>true, 'faqs'=> $projects],200);
    }
	public function teamList(Request $request){
		
		if($request->type == 'HomePage'){
			$projects = self::$Experts->where('status',1)->orderBy('id','DESC')->get()->take(2);
		}else if($request->type == 'AboutUs'){
			$projects = self::$Experts->where('status',1)->orderBy('id','DESC')->get()->take(3);
		}else{
			$projects = self::$Experts->where('status',1)->orderBy('id','DESC')->get();
		}
		
		foreach($projects as $key => $project){
			$projectBanner = 'blog-no-img.jpg';
			if($project->image != ""){
				$projectBanner = $project->image;
			}
			$project->expert_banner = env('APP_URL').'/public/admin/images/products/'.$projectBanner;
		}
		return response()->json(['success'=>true, 'teams'=> $projects],200);
    }
	public function serviceCategories(Request $request){
		
		$dataCenter = self::$MainServices->where('id',5)->first();
		$cybersecurity = self::$MainServices->where('id',6)->first();
		$cloud = self::$MainServices->where('id',7)->first();
		$infrastructure = self::$MainServices->where('id',4)->first();
		
		$dataCenter->image = env('APP_URL').'public/admin/images/products/'.$dataCenter->image;
		$cybersecurity->image = env('APP_URL').'public/admin/images/products/'.$cybersecurity->image;
		$cloud->image = env('APP_URL').'public/admin/images/products/'.$cloud->image;
		$infrastructure->image = env('APP_URL').'public/admin/images/products/'.$infrastructure->image;
		
		$result['dataCenter'] = $dataCenter;
		$result['cybersecurity'] = $cybersecurity;
		$result['cloud'] = $cloud;
		$result['infrastructure'] = $infrastructure;
		
		return response()->json(['success'=>true, 'result'=> $result],200);
    }
	public function projectsList(Request $request){
		
		if($request->type == 'HomePage'){
			$projects = self::$Products->where('status',1)->orderBy('id','DESC')->get()->take(4);
		}else{
			$projects = self::$Products->where('status',1)->orderBy('id','DESC')->get();
		}
		
		foreach($projects as $key => $project){
			$projectBanner = 'blog-no-img.jpg';
			if($project->image != ""){
				$projectBanner = $project->image;
			}
			$project->blog_banner = env('APP_URL').'/public/img/users/'.$projectBanner;
		}
		return response()->json(['success'=>true, 'projects'=> $projects],200);
    }
	
	#settingGet
    public function settingGet(Request $request){
		$record = self::$Settings->where('id',1)->first();
		return response()->json(['success'=>true, 'record'=> $record],200);
	}
	
	#getTestimonial
    public function getTestimonial(Request $request){

		$record = self::$Testimonials->where('status',1)->orderBy('id','DESC')->first();
		$record->user_profile = env('APP_URL').'public/admin/images/teams/'.$record->user_profile;
		return response()->json(['success'=>true, 'record'=> $record],200);
    }
	#newsletterSave
    public function newsletterSave(Request $request){

		$validator = Validator::make($request->all(), [
			'email' => 'required|email',
		],[
			'email.required' => 'Please enter email.',
			'email.email' => 'Please enter valid email.',
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
		}else{
			$setData['email'] = $request->email;
			$record = self::$Newsletter->CreateRecord($setData);
			return response()->json(['success'=>true, 'message'=> 'Newsletter saved successfully.'],200);
		}
    }
	#contactSave
    public function contactSave(Request $request){

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required|email',
			'phone' => 'required|numeric',
			'country' => 'required',
			'age' => 'required',
		],[
			'name.required' => 'Please enter name.',
			'email.required' => 'Please enter email.',
			'email.email' => 'Please enter valid email.',
			'phone.required' => 'Please enter phone.',
			'country.required' => 'Please enter country.',
			'age.required' => 'Please enter age.',
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
			if($errors->first('country')){
				return response()->json(['success'=>false, 'message' => $errors->first('country')]);
			}
			if($errors->first('age')){
				return response()->json(['success'=>false, 'message' => $errors->first('age')]);
			}
		}else{
			$setData['name'] = $request->name;
			$setData['email'] = $request->email;
			$setData['contact'] = $request->phone;
			$setData['country'] = $request->country;
			$setData['age'] = $request->age;
			
			$record = self::$Enquiries->CreateRecord($setData);
			return response()->json(['success'=>true, 'message'=> 'Enquiry send successfully.'],200);
		}
    }
	#membershipSave
    public function membershipSave(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'flat_no' => 'required',
			'building' => 'required',
			'street' => 'required',
			'area' => 'required',
			'landmark' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'postal_code' => 'required|numeric',
			'mobile' => 'required|numeric',
			'alt_mobile' => 'required|numeric',
			'land_line' => 'required|numeric',
			'email' => 'required|email',
			'pan_no' => 'required',
			'address_proof' => 'required',
			'address_proof_no' => 'required',
			'c_name' => 'required',
			'c_building' => 'required',
			'c_street' => 'required',
			'c_area' => 'required',
			'c_landmark' => 'required',
			'c_city' => 'required',
			'c_state' => 'required',
			'c_country' => 'required',
			'c_postal_code' => 'required|numeric',
			'c_mobile' => 'required|numeric',
			'c_alt_mobile' => 'required|numeric',
			'c_landline' => 'required|numeric',
			'c_email' => 'required',
			'salutation' => 'required',
			'b_name' => 'required',
			'b_gender' => 'required',
			'b_nationality' => 'required',
			'b_dob' => 'required',
			'b_relation' => 'required',
			'b_pan_no' => 'required',
			'b_address_proof' => 'required',
			'b_address_proof_no' => 'required'
		],[
			'name.required' => 'Please enter name.',
			'flat_no.required' => 'Please enter flat no.',
			'building.required' => 'Please enter building.',
			'street.required' => 'Please enter street.',
			'area.required' => 'Please enter area.',
			'landmark.required' => 'Please enter landmark.',
			'city.required' => 'Please enter city.',
			'state.required' => 'Please enter state.',
			'country.required' => 'Please enter country.',
			'postal_code.required' => 'Please enter postal code.',
			'mobile.required' => 'Please enter mobile.',
			'alt_mobile.required' => 'Please enter alt mobile.',
			'land_line.required' => 'Please enter land line.',
			'email.required' => 'Please enter email.',
			'pan_no.required' => 'Please enter pan no.',
			'address_proof.required' => 'Please enter address proof.',
			'address_proof_no.required' => 'Please enter address proof no.',
			'c_name.required' => 'Please enter name.',
			'c_building.required' => 'Please enter building.',
			'c_street.required' => 'Please enter street.',
			'c_area.required' => 'Please enter area.',
			'c_landmark.required' => 'Please enter landmark.',
			'c_city.required' => 'Please enter city.',
			'c_state.required' => 'Please enter state.',
			'c_country.required' => 'Please enter country.',
			'c_postal_code.required' => 'Please enter postal code.',
			'c_mobile.required' => 'Please enter mobile.',
			'c_alt_mobile.required' => 'Please enter alt mobile.',
			'c_landline.required' => 'Please enter landline.',
			'c_email.required' => 'Please enter email.',
			'salutation.required' => 'Please enter salutation.',
			'b_name.required' => 'Please enter name.',
			'b_gender.required' => 'Please enter gender.',
			'b_nationality.required' => 'Please enter nationality.',
			'b_dob.required' => 'Please enter dob.',
			'b_relation.required' => 'Please enter relation.',
			'b_pan_no.required' => 'Please enter pan no.',
			'b_address_proof.required' => 'Please enter address proof.',
			'b_address_proof_no.required' => 'Please enter address proof no.'
		]);
		if($validator->fails()){
			$errors = $validator->errors();
			if($errors->first('name')){
				return response()->json(['success'=>false, 'message' => $errors->first('name')]);
			}
			if($errors->first('flat_no')){
				return response()->json(['success'=>false, 'message' => $errors->first('flat_no')]);
			}
			if($errors->first('building')){
				return response()->json(['success'=>false, 'message' => $errors->first('building')]);
			}
			if($errors->first('street')){
				return response()->json(['success'=>false, 'message' => $errors->first('street')]);
			}
			if($errors->first('area')){
				return response()->json(['success'=>false, 'message' => $errors->first('area')]);
			}
			if($errors->first('landmark')){
				return response()->json(['success'=>false, 'message' => $errors->first('landmark')]);
			}
			if($errors->first('city')){
				return response()->json(['success'=>false, 'message' => $errors->first('city')]);
			}
			if($errors->first('state')){
				return response()->json(['success'=>false, 'message' => $errors->first('state')]);
			}
			if($errors->first('country')){
				return response()->json(['success'=>false, 'message' => $errors->first('country')]);
			}
			if($errors->first('postal_code')){
				return response()->json(['success'=>false, 'message' => $errors->first('postal_code')]);
			}
			if($errors->first('mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('mobile')]);
			}
			if($errors->first('alt_mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('alt_mobile')]);
			}
			if($errors->first('land_line')){
				return response()->json(['success'=>false, 'message' => $errors->first('land_line')]);
			}
			if($errors->first('email')){
				return response()->json(['success'=>false, 'message' => $errors->first('email')]);
			}
			if($errors->first('pan_no')){
				return response()->json(['success'=>false, 'message' => $errors->first('pan_no')]);
			}
			if($errors->first('address_proof')){
				return response()->json(['success'=>false, 'message' => $errors->first('address_proof')]);
			}
			if($errors->first('address_proof_no')){
				return response()->json(['success'=>false, 'message' => $errors->first('address_proof_no')]);
			}
			if($errors->first('c_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_name')]);
			}
			if($errors->first('c_building')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_building')]);
			}
			if($errors->first('c_street')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_street')]);
			}
			if($errors->first('c_area')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_area')]);
			}
			if($errors->first('c_landmark')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_landmark')]);
			}
			if($errors->first('c_city')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_city')]);
			}
			if($errors->first('c_state')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_state')]);
			}
			if($errors->first('c_country')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_country')]);
			}
			if($errors->first('c_postal_code')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_postal_code')]);
			}
			if($errors->first('c_mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_mobile')]);
			}
			if($errors->first('c_alt_mobile')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_alt_mobile')]);
			}
			if($errors->first('c_landline')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_landline')]);
			}
			if($errors->first('c_email')){
				return response()->json(['success'=>false, 'message' => $errors->first('c_email')]);
			}
			if($errors->first('salutation')){
				return response()->json(['success'=>false, 'message' => $errors->first('salutation')]);
			}
			if($errors->first('b_name')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_name')]);
			}
			if($errors->first('b_gender')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_gender')]);
			}
			if($errors->first('b_nationality')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_nationality')]);
			}
			if($errors->first('b_dob')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_dob')]);
			}
			if($errors->first('b_relation')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_relation')]);
			}
			if($errors->first('b_pan_no')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_pan_no')]);
			}
			if($errors->first('b_address_proof')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_address_proof')]);
			}
			if($errors->first('b_address_proof_no')){
				return response()->json(['success'=>false, 'message' => $errors->first('b_address_proof_no')]);
			}
			
		}else{
			$setData['name'] = $request->name;
			$setData['flat_no'] = $request->flat_no;
			$setData['building'] = $request->building;
			$setData['street'] = $request->street;
			$setData['area'] = $request->area;
			$setData['landmark'] = $request->landmark;
			$setData['city'] = $request->city;
			$setData['state'] = $request->state;
			$setData['country'] = $request->country;
			$setData['postal_code'] = $request->postal_code;
			$setData['mobile'] = $request->mobile;
			$setData['alt_mobile'] = $request->alt_mobile;
			$setData['land_line'] = $request->land_line;
			$setData['email'] = $request->email;
			$setData['pan_no'] = $request->pan_no;
			$setData['address_proof'] = $request->address_proof;
			$setData['address_proof_no'] = $request->address_proof_no;
			$setData['c_name'] = $request->c_name;
			$setData['c_building'] = $request->c_building;
			$setData['c_street'] = $request->c_street;
			$setData['c_area'] = $request->c_area;
			$setData['c_landmark'] = $request->c_landmark;
			$setData['c_city'] = $request->c_city;
			$setData['c_state'] = $request->c_state;
			$setData['c_country'] = $request->c_country;
			$setData['c_postal_code'] = $request->c_postal_code;
			$setData['c_mobile'] = $request->c_mobile;
			$setData['c_alt_mobile'] = $request->c_alt_mobile;
			$setData['c_landline'] = $request->c_landline;
			$setData['c_email'] = $request->c_email;
			$setData['salutation'] = $request->salutation;
			$setData['b_name'] = $request->b_name;
			$setData['b_gender'] = $request->b_gender;
			$setData['b_nationality'] = $request->b_nationality;
			$setData['b_dob'] = $request->b_dob;
			$setData['b_relation'] = $request->b_relation;
			$setData['b_pan_no'] = $request->b_pan_no;
			$setData['b_address_proof'] = $request->b_address_proof;
			$setData['b_address_proof_no'] = $request->b_address_proof_no;
			
			
			$record = self::$MembershipRecords->CreateRecord($setData);
			return response()->json(['success'=>true, 'message'=> 'Data send successfully.'],200);
		}
    }
}
