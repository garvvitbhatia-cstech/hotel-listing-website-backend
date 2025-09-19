<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Orders;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use ReallySimpleJWT\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Languages;
use Session;
use Validator;
use Mail;
use URL;
use Cookie;
use Illuminate\Validation\Rule;

class OrdersController extends Controller
{
	private static $Orders;
    private static $TokenHelper;
	public function __construct(){
        self::$TokenHelper = new TokenHelper();
		self::$Orders = new Orders();
	}

    #admin dashboard page
    public function getList(Request $request){
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}
        return view('/admin/orders/index');
    }
    public function listPaginate(Request $request){
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
		$records =  $query->orderBy('id', 'DESC')->simplePaginate(20);
        return view('/admin/orders/paginate',compact('records'));
    }

    #edit Service Type
    public function viewPage(Request $request, $row_id){
		$RowID =  base64_decode($row_id);
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

		$rowData = self::$Orders->where(array('id' => $RowID))->first();
        if(isset($rowData->id)){
            return view('/admin/orders/view-page',compact('rowData','row_id'));
        }else{
            return redirect('/admin/orders');
        }
    }
    #edit Service Type
    public function updateOrderStatus(Request $request){
		$RowID =  $request->row;
        $order_status =  $request->value;
		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

		$rowData = self::$Orders->where(array('id' => $RowID))->first();
        if(isset($rowData->id)){
            $setData['id'] = $rowData->id;
            $setData['order_status'] = $order_status;
            self::$Orders->UpdateRecord($setData); 
        }
        echo "Success";die;
    }
    
}
