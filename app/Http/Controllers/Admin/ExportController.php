<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Enquiries;

use App\Models\User;

use App\Models\EmployeeAttendence;

use App\Models\Products;

use App\Models\Orders;

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

use App\Http\Controllers\Admin\GeneralController;


class ExportController extends Controller{


	private static $Enquiries;

	private static $EmployeeAttendence;

	private static $Orders;

	private static $Products;

	private static $User;


	public function __construct(){

		self::$Enquiries = new Enquiries();

		self::$Products = new Products();

		self::$EmployeeAttendence = new EmployeeAttendence();

		self::$Orders = new Orders();

		self::$User = new User();

	}

	#exportProduct
    public function exportEmployeeAttendance(Request $request){
        if(!$request->session()->has('admin_email')){ echo 'SessionExpired';  die; }
        $query = self::$EmployeeAttendence->where('status', '!=', 3);
        if($request->session()->get('admin_id') > 1 && $request->session()->get('admin_type') == 'Staff'){
            $query->where('employee_id', $request->session()->get('admin_id'));
        }
        if($request->input('day') && $request->input('day') != ""){
            $day = $request->input('day');
            $query->where('day', $day);
        }
        if($request->input('month') && $request->input('month') != ""){
            $month = $request->input('month');
            $query->where('month', $month);
        }
        if($request->input('in_status') && $request->input('in_status') != ""){
            $in_status = $request->input('in_status');
            $query->where('in_status', $in_status);
        }
        if($request->input('year') && $request->input('year') != ""){
            $year = $request->input('year');
            $query->where('year', $year);
        }
        if($request->input('name') && $request->input('name') != ""){
            $name = $request->input('name');
            $data = DB::table('users')->where('name', 'like', '%' . $name . '%')->where('status', '!=', 3)->get();
            foreach ($data as $key => $value){
                if(isset($value->id)){
                    $setDatas[] = $value->id;
                }
            }
            $implode = implode(',', array_unique($setDatas));
            $query->whereRaw('FIND_IN_SET(employee_id, ?)', [$implode]);
        }
        if($request->input('employee_id') && $request->input('employee_id') != ""){
            $employee_id = $request->input('employee_id');
            $data = DB::table('users')->where('emp_id', 'like', '%' . $employee_id . '%')->where('status', '!=', 3)->get();
            foreach ($data as $key => $value){
                if(isset($value->id)){
                    $setDatas[] = $value->id;
                }
            }
            $implode = implode(',', array_unique($setDatas));
            $query->whereRaw('FIND_IN_SET(employee_id, ?)', [$implode]);
        }
        if(!empty($request->input('from_date')) || !empty($request->input('to_date'))){
            $fdate = $tdate = '';
            if(!empty($request->input('from_date'))){
                $fdate = date('Y-m-d', strtotime($request->input('from_date')));
            }
            if(!empty($request->input('to_date'))){
                $tdate = date('Y-m-d', strtotime($request->input('to_date')));
            }            
            if(!empty($fdate) && empty($tdate)){
                $query->where('date', '>=', $fdate);
            }else if(empty($fdate) && !empty($tdate)){
                $query->where('date', '<=', $tdate);
            }else{
                $query->whereBetween(DB::raw('DATE(date)'), [$fdate, $tdate]);
            }
        }
        $records = $query->orderBy('id', 'DESC')->get();
        $delimiter = ",";
        $filename = "attendance_" . date('d_F_Y') . ".csv";
        $destination = "storage/csv/" . $filename;
        //create a file pointer
        $f = fopen($destination, "w");
        //set column headers
        $fields = array(
			'S.No',
			'Employee Name', 
			'Latitude', 
			'Longitude', 
			'Date', 
			'Check IN', 
			'Check OUT', 
			'Status', 
			'Note', 
			'Created'
		);
        fputcsv($f, $fields, $delimiter);
        foreach ($records as $key => $record):
            $user = $this->getUser($record->employee_id);
            $status = $record->in_status;
            $lineData = array(
				$key + 1,
				$user->name, 
				$record->latitude, 
				$record->longitude, 
				$record->date, 
				$record->check_in, 
				$record->check_out, 
				$status, 
				$record->message, 
				$record->created_at
			);
            fputcsv($f, $lineData, $delimiter);
        endforeach;
        $lineData2 = array('', '');
        fputcsv($f, $lineData2, $delimiter);
        fclose($f);
        echo env('APP_URL') . $destination;
        exit;
    }
	

	#exportProduct

    public function exportProduct(Request $request){

		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }

        //$query = self::$Products->where('status', '!=', 3);

		$admin_type = $request->session()->get('admin_type');

		$admin_id = $request->session()->get('admin_id');	

		$query = self::$Products->where('products.status', '!=', 3);


		if($request->input('title')  && $request->input('title') != ""){

            $SearchKeyword = $request->input('title');

            $query->where('products.title', 'like', '%'.$SearchKeyword.'%');

		}

		$records =  $query->orderBy('id', 'DESC')->get();

		$delimiter = ",";

		$filename = "products_" . date('d_F_Y') . ".csv";

		$destination = "storage/csv/".$filename;

		//create a file pointer

		$f = fopen($destination,"w");

		//set column headers

		$fields = array(

						'S.No', 

						'Title',						 

						'Description',

						'SEO Title',

						'SEO Keywords',

						'SEO Description',

						'SEO Robots',

						'Status',

						'Created'

					);		 

		fputcsv($f, $fields, $delimiter);

		foreach($records as $key => $record):

			if($record->status == 1){

				$status = 'Active';

			}else{

				$status = 'Inactive';

			}

			$lineData = array(

						$key+1,

						$record->title,

							

						strip_tags($record->description),

						$record->seo_title,

						$record->seo_description,

						$record->seo_keyword,

						$record->robot_tags,

						$status,

						$record->created_at,                           

					);

			fputcsv($f, $lineData, $delimiter);

		endforeach;

		$lineData2 = array('','');						

		fputcsv($f, $lineData2, $delimiter);                     
	
		fclose ($f);

		echo env('APP_URL').$destination;		

		exit;

    }

	

	#exportProduct

    public function exportOrders(Request $request){

		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }

        if(!$request->session()->has('admin_email')){return redirect('/admin/');}

		$query = self::$Orders->where('status', '!=', 3);

		if($request->input('order_status')  && $request->input('order_status') != ""){

            $order_status = $request->input('order_status');

            $query->where('order_status', $order_status);

		}		

		if($request->input('customer_name')  && $request->input('customer_name') != ""){

            $customer_name = $request->input('customer_name');

            $query->where('customer_name', 'like', '%'.$customer_name.'%');

		}

        if(!empty($request->input('from_date')) || !empty($request->input('to_date'))){

            $fdate = date('d-m-Y',strtotime($request->input('from_date')));

            $tdate = date('d-m-Y',strtotime($request->input('to_date').'11:59:59 pm'));

            if(!empty($fdate) && empty($tdate)){

				$query->where('order_date', '>=', $fdate);

            }else if(empty($fdate) && !empty($tdate)){

				$query->where('order_date', '<=', $tdate);

            }else{

				$query->where('order_date', '>=', $fdate);

				$query->where('order_date', '<=', $tdate);

            }

        }

		$records =  $query->orderBy('id', 'DESC')->get();

		

		$delimiter = ",";

		$filename = "orders_" . date('d_F_Y') . ".csv";

		$destination = "storage/csv/".$filename;



		//create a file pointer

		$f = fopen($destination,"w");	



		//set column headers

		$fields = array(

						'S.No', 

						'Invoice',						

						'Name',

						'Email',

						'Phone',

						'Address',

						'Country',

						'City',						

						'State',

						'Zipcode',

						'Product',

						'Order Date',

						'Coupon Code',						

						'Sub Total',						

						'Discount',						

						'Total',

						'Order Status',						

						'Created'

					); 



		fputcsv($f, $fields, $delimiter);

		foreach($records as $key => $record):

			$created_at = date('d-m-Y h:ia',strtotime($record->created_at));

			$productDetails = $this->getProduct($record->product_id);

			$product_name = '';

			if(isset($productDetails->id)){

				$product_name = $productDetails->title;

			}

			$lineData = array(

						$key+1,

						$record->invoice_id,						

						$record->customer_name,

						$record->customer_email,

						$record->customer_mobile,

						$record->customer_address,

						$record->customer_city,

						$record->customer_state,

						$record->customer_country,

						$record->customer_zipcode,

						$product_name,

						$record->order_date,

						$record->coupon_code,						

						$record->total + $record->discount,						

						$record->discount,						

						$record->total,

						$record->order_status,

						$created_at,

					);

			fputcsv($f, $lineData, $delimiter);

		endforeach;



		$lineData2 = array('','');

		fputcsv($f, $lineData2, $delimiter);

		fclose ($f);

		echo env('APP_URL').$destination;

		exit;

    }



	#exportProduct

    public function exportEnquiries(Request $request){		

		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }

        $query = self::$Enquiries->where('status', '!=', 3);

		if($request->input('name')  && $request->input('name') != ""){

            $SearchKeyword = $request->input('name');

            $query->where('name', 'like', '%'.$SearchKeyword.'%');

		}

		if($request->input('type')  && $request->input('type') != ""){

            $type = $request->input('type');

            $query->where('type', $type);

		}

		if($request->input('read_status')  && $request->input('read_status') != ""){

            $SearchKeyword = $request->input('read_status');

            $query->where('read_status', 'like', '%'.$SearchKeyword.'%');

		}

		$records =  $query->orderBy('id', 'DESC')->get();



		$delimiter = ",";

		$filename = "enquiries_" . date('d_F_Y') . ".csv";

		

		$destination = "storage/csv/".$filename;

		//create a file pointer

		$f = fopen($destination,"w");

		

		//set column headers

		$fields = array(

						'S.No',

						'Product',

						'Name',

						'email',

						'Phone',

						'Message',

						'Status',

						'Created'

						);

		 

		fputcsv($f, $fields, $delimiter);

		foreach($records as $key => $record):

			$status = '';

			if($record->read_status == 1){

				$status = 'Read';

			}

			if($record->read_status == 2){

				$status = 'Unread';

			}
			$product = NULL;
			if($record->product_id > 0){
				$product_details = $this->getProduct($record->product_id);
				$product = $product_details->title;
			}

			$lineData = array(

							$key+1,

							$product,

							$record->name,

							$record->email,

							$record->contact,

							$record->message,

							$status,

							$record->created_at,                           

						);

			fputcsv($f, $lineData, $delimiter);


		endforeach;

		$lineData2 = array('','');						

		fputcsv($f, $lineData2, $delimiter);                     
		
		fclose ($f);

		echo env('APP_URL').$destination;		

		exit;

    }

	public function getUser($uid){
        return self::$User->where('id', $uid)->first();
    }

	public function getProduct($pid){

		return self::$Products->where('id',$pid)->first();

	}



}