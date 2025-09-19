<?php
namespace App\Helpers;

use DB;

use Session;

class Helper{

	public static function settings(){

		$records = DB::table('settings')->where(array('id' => 1))->first();

        return $records;

	}

	public static function getService($id){

		$records = DB::table('services')->where(array('id' => $id))->first();

        return $records;

	}

	public static function getCategory($id = NULL){

		$record = DB::table('categories')->where(array('id' => $id))->first();

        return $record->title;

	}

	public static function getSubCategoryTitle($id = NULL){

		$record = DB::table('sub_categories')->where(array('id' => $id))->first();

		if(isset($record->id)){
			return $record->title;
		}else{
			return '';
		}        

	}

	public static function getSubCategoryList($category_id = NULL){
		
		return DB::table('sub_categories')->where(array('category_id' => $category_id,'status' => 1))->pluck('title','id');

	}

	public static function getTotalProduct($id,$type){

		$query = DB::table('products');

		$query->where('status',1);

		if($type == 'Vendor'){

			$query->where('vendor_id',$id);

		}

		$records = $query->count();

        return $records;

	}

	public static function getTotalProductOut($id,$type){

		$query = DB::table('products');

		$query->where('status',1);

		$query->where('stock','YES');

		if($type == 'Vendor'){

			$query->where('vendor_id',$id);

		}

		$records = $query->count();

        return $records;

	}

	public static function getTotalCustomer($id,$type){

		$query = DB::table('users');

		$query->where('status',1);

		$records = $query->count();

        return $records;

	}

	public static function getTotalOrder($id,$type){

		$query = DB::table('orders');

		$query->where('payment_status','SUCCESS');

		if($type == 'Vendor'){

			//$query->where('vendor_id',$id);

		}

		$records = $query->count();

        return $records;

	}

	public static function getTotalOrderAmount($id,$type){

		$query = DB::table('orders');

		$query->where('payment_status','SUCCESS');

		if($type == 'Vendor'){

			//$query->where('vendor_id',$id);

		}

		$records = $query->get();

		$totalAmount = 0;

		foreach($records as $key => $record){

			$totalAmount = $totalAmount+$record->grand_total;

		}

        return number_format($totalAmount,2);

	}

	public static function getNoOfProducts($ids){

		$explode = explode(',',$ids);

		$totalCount = 0;

		foreach($explode as $key => $singleID){

			$records = DB::table('products')->whereRaw('FIND_IN_SET('.$singleID.', ailment_id)')->count();

			$totalCount = $totalCount+$records;

		}		

        return $totalCount;

	}

	public static function getProduct($id = NULL, $field = NULL) {

        if ($field != '') {

            $records = DB::table('products')->where(array('id' => $id))->first();

            return $records->$field;

        } else {

            return DB::table('products')->where(array('id' => $id))->first();

        }

    }

	public static function getProductInfo($id){

		$records = DB::table('products')->where(array('id' => $id))->first();

        return $records;

	}

	public static function getUserInfo($id){

		if($id > 0){

			$records = DB::table('users')->where(array('id' => $id))->first();

        	return $records;

		}else{

			return '';

		}

	}

	public static function getUserName($id){

		if($id > 0){

			$records = DB::table('users')->where(array('id' => $id))->first();

        	return $records->name;

		}else{

			return '';

		}

	}

	public static function getTestimonial(){

		$records = DB::table('testimonials')->where(array('status' => 1))->latest()->get();

        return $records;

	}

	public static function getOrderItems($id){

		$records = DB::table('order_items')->where(array('order_id' => $id))->get();

        return $records;

	}

	public static function getProductImages($id){		

		$productImages = DB::table('product_images')->where(array('product_id' => $id))->orderBy('img_ordering', 'ASC')->get();

		$count = 0;

		foreach($productImages as $key => $val){

			if(!empty($val->image)){

				$data = $val->image;

				$count = 1;

				break;	

			}

		}

		$response = '';

		if($count == 1){

			$response = $data;

		}		

        return $response;

	}
	

	public static function getSellProductStatus($pid,$vendor_id){

		$record = DB::table('products')->where(array('vendor_pid' => $pid,'vendor_id' => $vendor_id))->first();

		$status = NULL;

		if(isset($record->id)){

			$status = $record->vendor_sale_status;

		}

		return $status;

	}
	

	public static function getOrderStatus($ordId = NULL,$field = NULL){

		if($ordId != ''){

			$query = DB::table('orders')->select($field)->where('id',$ordId)->first();			

			return $query->$field;

		}else{

			return NULL;

		}		

	}
	

	public static function getShippingMethod($shipping_id = NULL,$field = NULL){

		if($shipping_id != ''){

			$query = DB::table('shipping_methods')->select($field)->where('id',$shipping_id)->first();			

			return $query->$field;

		}else{

			return NULL;

		}

	}
	

	public static function getPrescriptionOrder($order_id = NULL){

		$row_id = NULL;

		if($order_id != ''){

			$query = DB::table('orders')->select(['id'])->where('prescription_order_id',$order_id)->first();

			if(isset($query->id)){

				$row_id = $query->id;

			}

		}

		return $row_id;

	}
	

	public static function getOrder($ordId = NULL){

		if($ordId != ''){

			return DB::table('orders')->where('id',$ordId)->first();

		}else{

			return NULL;

		}		

	}
	

	public static function getSubCategory($categoryList=NULL,$parentId=NULL,$editId=NULL){		

		$list = '<option value="0">Root</option>';

		if(!empty($categoryList)){

			foreach($categoryList as $keys => $vals):

				$seleted = '';

				$disabled = '';

				$newList = DB::table('categories')->where('parent_id',$vals->id)->get();

				if($parentId == $vals->id){$seleted = 'selected="selected"';}

				$list .= '<option '.$seleted.' value="'.$vals->id.'">'.ucwords($vals->title).'</option>';

				foreach($newList as $nKey => $nVal):

					$seleted2 = $seleted3 = '';

					$disabled2 = $disabled3 = '';

						if($parentId == $nVal->id){$seleted2 = 'selected="selected"';}

						$list .= '<option '.$seleted2.' value="'.$nVal->id.'"> → '.ucwords($nVal->title).'</option>';

						$newList2 = DB::table('categories')->where('parent_id',$nVal->id)->get();

						foreach($newList2 as $nKey => $nVal2):

						$seleted3 = '';

						$disabled3 = '';

						if($parentId == $nVal2->id){$seleted3 = 'selected="selected"';}

						$list .= '<option '.$seleted3.' value="'.$nVal2->id.'"> → → '.ucwords($nVal2->title).'</option>';

						endforeach;

				endforeach;

			endforeach;

			return $list;

		}

	}
	

	#get sub category  data

    public static function getNavigationCategory($categoryList=NULL,$parentId=NULL,$editId=NULL){

		$list = '<option value="0">Root</option>';

		if(!empty($categoryList)){

			foreach($categoryList as $keys => $vals):

				$seleted = '';

				$disabled = '';

				$menuPageTitle = '';

				$newList = DB::table('header_navigations')->where('parent_id',$keys)->get();

				if($parentId == $keys){$seleted = 'selected="selected"';}

				if($editId == $keys){$disabled = 'disabled="disabled"';}

				$list .= '<option '.$seleted.' '.$disabled.' value="'.$keys.'">'.ucwords($vals).'</option>';				

				foreach($newList as $nKey => $nVal):

					if(isset($nVal->menu_page_id) && !empty($nVal->menu_page_id)){

						$menuPageTitle = $this->getCmsPagesTitle($nVal->menu_page_id);	

						$list .= '<option disabled="disabled" value=""> → '.$menuPageTitle.'</option>';

					}					

				endforeach;				

			endforeach;			

			return $list;

		}        

	}

	public static function checkTodayAttendence($emp_id = NULL){
        $record = '';
        if($emp_id != ''){
            $date = date('Y-m-d');
            $record = DB::table('attendence')->where('employee_id', $emp_id)->where('date', $date)->where('status', '!=', 3)->first();
        }
        return $record;
    }

    public static function getTotalPresent($emp_id = NULL, $month = NULL, $year = NULL){
        $record = 0;
        if($emp_id != '' && $month != '' && $year != ''){
            $record = DB::table('attendence')->where('employee_id', $emp_id)->where('month', $month)->where('year', $year)->where('in_status', 'Present')->where('status', '!=', 3)->count();
        }
        return $record;
    }

    public static function getTotalAbsent($emp_id = NULL, $month = NULL, $year = NULL){
        $count = 0;
        if($emp_id != '' && $month != '' && $year != ''){
            $count_absent = DB::table('attendence')->where('employee_id', $emp_id)->where('month', $month)->where('year', $year)->where('in_status', 'Absent')->where('status', '!=', 3)->count();
            $count_half_day = DB::table('attendence')->where('employee_id', $emp_id)->where('month', $month)->where('year', $year)->where('in_status', 'Half Day')->where('status', '!=', 3)->count();
            if($count_half_day > 0){
                if($count_half_day % 2 != 0){
                    $count_half_day = $count_half_day - 1;
                }
            }
            $count = $count_absent + $count_half_day;
        }
        return $count;
    }

    public static function getTodayPresent(){
        $today_date = date('Y-m-d');
        $month = date('m');
        $year = date('Y');
        $day = date('d');
        return DB::table('attendence')->where('day', $day)->where('month', $month)->where('year', $year)->where('in_status', 'Present')->where('status', '!=', 3)->count();
    }

	public static function getCategoryList(){
		
		return DB::table('categories')->where('status',1)->orderBy('title')->get();

	}

	public static function getSubCategoryListFront($category_id = NULl){
		
		return DB::table('sub_categories')->where('status',1)->where('category_id',$category_id)->orderBy('title')->get();

	}
	

}

?>