<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\RouteHelper;
use App\Models\TokenHelper;
use App\Models\Responses;
use App\Models\ShippingMethods;
use App\Models\PaymentMethods;
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
use App\Models\Reviews;


class ImportController extends Controller
{
	private static $User;	
		
	public function __construct(){
		self::$User = new User();		
	}
	
	#importProduct
    public function importProduct(Request $request){		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$admin_id = $request->session()->get('admin_id');
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				$error = array();
				
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					$error_count = 0;	
					if($num > 1){
						$count = self::$Products->where('product_name',trim($column[1]))->count();
						if($count == 0){							
							$setData['product_name'] = trim($column[1]);
							$setData['slug'] = $this->builtSlug($setData['product_name']);	
							$count_brand = self::$Brands->where('status',1)->where('title',$column[2])->first();
							if(isset($count_brand->id)){
								$setData['brand_id'] = trim($count_brand['id']);
							}else{
								$error['brand'][] = trim($column[2]);
								$error_count = 1;
							}
							$count_categories = self::$Categories->where('status', 1)->where('title',$column[3])->first();
							if(isset($count_categories->id)){
								$setData['category_id'] = trim($count_categories['id']);
							}else{
								$error['category_id'][] = trim($column[3]);
								$error_count = 1;
							}
							if(isset($setData['category_id'])){
								$subcat_array = array();
								if($column[4] != ''){								
									$subcat_ids_arr = explode('|',$column[4]);
									foreach($subcat_ids_arr as $key => $subcat_title){
										$subcat_data = self::$SubCategories->where('status',1)->where('title',trim($subcat_title))->first();
										if(isset($subcat_data->id)){										
											if(in_array($setData['category_id'], explode(',',$subcat_data->category_id))){
												$subcat_array[] = $subcat_data->id;
											}else{
												$error['sub_category_id'][] = trim($subcat_title);	
												$error_count = 1;
											}
										}else{
											$error['sub_category_id'][] = trim($subcat_title);	
											$error_count = 1;
										}
									}
								}
								$setData['sub_category_id'] = trim(implode(',',$subcat_array));
							}
							$setData['product_type'] = $column[5];
							$ailments_array = array();
							if($column[6] != ''){
								$ailments_ids_arr = explode('|',$column[6]);
								foreach($ailments_ids_arr as $key => $ailment_title){
									$ailment = self::$Ailments->where('status',1)->where('title',trim($ailment_title))->first();
									if(isset($ailment->id)){
										$ailments_array[] = $ailment->id;
									}else{
										$error['ailment_id'][] = trim($ailment_title);	
									}
								}
							}							
							$setData['ailment_id'] = trim(implode(',',$ailments_array));													
							
							$setData['hsn_code'] = $column[7];
							if($column[8] != ''){
								$setData['mrp'] = $column[8];
							}
							if($column[9] != ''){
								$setData['list_price'] = $column[9];
							}
							if($column[10] != ''){
								$setData['packaging_cost'] = $column[10];
							}
							if($column[11] != ''){
								$setData['discounted_price'] = $column[11];
							}
							$setData['tax'] = $column[12];
							$setData['is_tax_included'] = $column[13];
							$setData['product_qty'] = $column[14];
							$setData['minimum_order_qty'] = $column[15];
							$setData['maximum_order_qty'] = $column[16];
							$setData['is_show_register_user'] = $column[17];
							$setData['is_show_all_user'] = $column[18];
							$setData['is_show_guest_user'] = $column[19];
							$setData['is_prescription_required'] = $column[20];
							$setData['product_weight'] = $column[21];
							$setData['product_width'] = $column[22];
							$setData['product_height'] = $column[23];
							$setData['product_length'] = $column[24];
							if($column[25] != ''){
								$setData['shipping_cost'] = number_format($column[25],2);
							}
							$setData['is_free_shipping'] = $column[26];
							$setData['is_self_ship'] = $column[27];
							$setData['stock'] = $column[28];
							if($column[29] != ''){
								$setData['stock_date'] = date('Y-m-d',strtotime($column[29]));
							}
							$setData['moderation_status'] = $column[30];
							$setData['moderation_decline_reason'] = $column[31];
							
							if($column[32] != ''){
								$vendorData = self::$User->where('status',1)->where('name',trim($column[32]))->first();
								if(isset($vendorData->id)){
									$setData['vendor_id'] = $vendorData->id;
								}else{
									$error['vendor_id'][] = trim($column[32]);	
								}
							}							
							$setData['pack_unit'] = $column[33];
							if($column[34] != ''){
								$setData['pack_size'] = $column[34];
							}
							$setData['special_notes'] = $column[35];
							$setData['short_description'] = $column[36];
							$setData['long_description'] = $column[37];
							$setData['seo_title'] = $column[38];
							$setData['seo_keywords'] = $column[39];
							$setData['seo_description'] = $column[40];
							if($column[41] != ''){
								$setData['status'] = $column[41];
							}
							if($error_count == 0){
								if($product_rec = self::$Products->CreateRecord($setData)){
									$this->updateProductCode($request, $product_rec->id,$admin_id);
								}								
							}							
						}
					}
					$num++;
				}				
				/*
				if(count($error) > 0){
					$delimiter = ",";
					$filename = "product_error_" . date('d_F_Y') . ".csv";
					
					$destination = "storage/csv/".$filename;
					//create a file pointer
					$f = fopen($destination,"w");
					
					$fields = array(
						
					);
					
					fputcsv($f, $fields, $delimiter);
					foreach($error as $key => $record):
					print_r($record);
						$lineData = array(
							$key+1,
							
						);
						fputcsv($f, $lineData, $delimiter);
					endforeach;
					$lineData2 = array('','');						
					fputcsv($f, $lineData2, $delimiter);                     
					
					fclose ($f);
					die;
					echo env('APP_URL').$destination;	die;
				}
				*/				
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	
	/************** updateProductCode ************/
	public function updateProductCode($request, $rowID,$admin_id ){
		$productUniqueCode = "00000000".$rowID;
        $productCodeStr = "AB-";
        $productCodeVendor = "AB-";
		$vendor_id = NULL;
        $productUniqueCode = substr($productUniqueCode, -8);
        if($admin_id != '' && $admin_id > 1){
            $vendorData = self::$User->where('id',$admin_id)->first();
            if(isset($vendorData->id) && $vendorData->id > 0){
                $vendorName = $vendorData->name;
                preg_match_all('/\b\w/', $vendorName, $matches);
                $productCodeVendor = implode('', $matches[0]);
                $productCodeVendor = $productCodeVendor.'-';
				$vendor_id = $vendorData->id;
            }
        }
        $productCode = $productCodeStr.$productCodeVendor.$productUniqueCode;
        DB::table("products")->where(array('id' => $rowID))->update(array('product_code' => $productCode, 'vendor_id' => $vendor_id));

		return true;
	}
	
	#importVendor
    public function importVendor(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$User->where('email',$column[2])->count();
						if($count == 0){
							$setData['type'] = 'Vendor';
							$setData['name'] = $column[1];
							$setData['email'] = $column[2];
							$setData['mobile'] = $column[3];
							$setData['address'] = $column[4];
							$setData['city'] = $column[5];
							$setData['zipcode'] = $column[6];
							$setData['state'] = $column[7];
							$setData['country'] = $column[8];
							$record = self::$User->CreateRecord($setData);
							
							$setData2['user_id'] = $record->id;
							$setData2['language_id'] = $column[9];
							$setData2['is_admin_account'] = $column[10];
							$setData2['hide_vendor'] = $column[11];
							$setData2['company_type_id'] = $column[12];
							$setData2['gst_no'] = $column[13];
							$setData2['license_type_id'] = $column[14];
							$setData2['license_no'] = $column[15];
							$setData2['seo_title'] = $column[16];
							$setData2['seo_description'] = $column[17];
							$setData2['seo_keywords'] = $column[18];
							$setData2['description'] = $column[19];
							$setData2['plan_id'] = $column[20];
							$setData2['commission'] = $column[21];
							$setData2['bank_name'] = $column[22];
							$setData2['ac_no'] = $column[23];
							$setData2['ac_type'] = $column[24];
							$setData2['ifsc'] = $column[25];
							$setData2['ac_holder_name'] = $column[26];
							self::$VendorDetails->CreateRecord($setData2);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
    #importAilments
    public function importAilments(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Ailments->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							if($column[3] && !empty($column[3])){
								$imgurl = $column[3];
								$url = trim($imgurl);
								if(!empty($url)){
									$destination = base_path().'/public/img/ailments/';
									$image_name = $this->uploadImgUrl($url,$destination);
									$setData['image'] = $image_name;
								}
							}
							$record = self::$Ailments->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importLabTest
    public function importLabTest(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$LabTests->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$record = self::$LabTests->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importSpeciality
    public function importSpeciality(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Specialties->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$record = self::$Specialties->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importCategory
    public function importCategory(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Categories->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$setData['seo_title'] = $column[3];
							$setData['seo_keywords'] = $column[4];
							$setData['seo_description'] = $column[5];
							if($column[6] && !empty($column[6])){
								$imgurl = $column[6];
								$url = trim($imgurl);
								if(!empty($url)){
									$destination = base_path().'/public/img/categories/';
									$image_name = $this->uploadImgUrl($url,$destination);
									$setData['image'] = $image_name;
								}
							}
							$record = self::$Categories->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importPaymentMethod
    public function importPaymentMethod(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$PaymentMethods->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$setData['type'] = $column[3];
							$setData['surcharge_percentage'] = $column[4];
							$setData['surcharge_rupees'] = $column[5];
							$setData['allow_for'] = $column[6];
							$setData['key_id'] = $column[7];
							$setData['key_secret'] = $column[8];
							$record = self::$PaymentMethods->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importShippingMethod
    public function importShippingMethod(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$ShippingMethods->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$setData['rate_calculation'] = $column[3];
							$setData['delivery'] = $column[4];
							$setData['weight_limit'] = $column[5];
							$record = self::$ShippingMethods->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importCouponCode
    public function importCouponCode(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						$types = array('Product','Category','User','Country','State','City','Pincode','Doctor','Vendor','User Subscription','Corporate Subscription','Order','Cart','Payment Method');
						$count = self::$CouponCodes->where('title',trim($column[2]))->count();
						if($count == 0){							
							if($column[3] == 'Percent' || $column[3] == 'Amount'){
								if($column[8] != '' && $column[9] != ''){
									$start_date = date('Y-m-d',strtotime($column[8]));
									$end_date = date('Y-m-d',strtotime($column[9]));
									if($end_date >= $start_date){
										$setData['start_date'] = $start_date;
										$setData['end_date'] = $end_date;
										$setData['title'] = strtoupper(strtolower(trim($column[2])));
										$setData['discount_type'] = $column[3];
										$setData['amount'] = $column[4];
										$setData['percentage'] = $column[5];
										$setData['no_of_user'] = $column[6];
										$setData['no_of_time'] = $column[7];
										$setData['apply_multiple_coupon'] = ($column[10] == 'Yes'?1:2);
										$setData['show_on_checkout'] = ($column[11] == 'Yes'?1:2);
										$setData['is_active'] = ($column[12] == 'Yes'?1:2);
										$setData['is_applicable_on_visit'] = ($column[13] == 'Yes'?1:2);
										$setData['is_applicable_on_walk_in'] = ($column[14] == 'Yes'?1:2);
										$setData['is_applicable_on_chat'] = ($column[15] == 'Yes'?1:2);
										$setData['is_applicable_on_audio'] = ($column[16] == 'Yes'?1:2);
										$setData['is_applicable_on_video'] =($column[17] == 'Yes'?1:2);										
										if($column[1] != ''){
											if(in_array($column[1],$types)){
												$setData['type'] = $column[1];												
											}
										}
										$product_array = $categories_array = array();										
										if(isset($column[18]) && !empty($column[18])){
											$explode_CAT = explode('|',$column[18]);
											foreach($explode_CAT as $key => $category){
												$cat = self::$Categories->where('status',1)->where('title',trim($category))->first();
												if(isset($cat->id)){
													$categories_array[] = $cat->id;
												}														
											}	
											if(count($categories_array) > 0){
												$categories_implode = implode(',',$categories_array);
												$setData['category_id'] = $categories_implode;
											}
										}
										if(isset($column[19]) && !empty($column[19])){
											$explode_products = explode('|',$column[19]);
											foreach($explode_products as $key => $product){
												$prd = self::$Products->where('status',1)->where('product_name',trim($product))->first();
												if(isset($prd->id)){
													$product_array[] = $prd->id;
												}														
											}	
											if(count($product_array) > 0){
												$products_implode = implode(',',$product_array);
												$setData['product_id'] = $products_implode;
											}
										}
										if(isset($column[20]) && !empty($column[20])){
											$explode_users = explode('|',$column[20]);
											$user_array = array();
											foreach($explode_users as $key => $user){
												$user = self::$User->where('status',1)->where('type','User')->where('name',trim($user))->first();
												if(isset($user->id)){
													$user_array[] = $user->id;
												}														
											}	
											if(count($user_array) > 0){
												$user_implode = implode(',',$user_array);
												$setData['user_id'] = $user_implode;
											}
										}
										if(isset($column[21]) && !empty($column[21])){
											$country = self::$Country->where('status',1)->where('country_name',trim($column[21]))->first();
											if(isset($country->id)){
												$setData['country_id'] = $country->id;
											}														
										}
										if(isset($column[22]) && !empty($column[22])){
											$state = self::$State->where('status',1)->where('state',trim($column[22]))->first();
											if(isset($state->id)){
												$setData['state_id'] = $state->id;
											}														
										}
										if(isset($column[24]) && !empty($column[24])){
											$user_subscription = self::$Subscriptions->where('status',1)->where('type','User')->where('title',trim($column[24]))->first();
											if(isset($user_subscription->id)){
												$setData['user_subscription_id'] = $user_subscription->id;
											}														
										}
										if(isset($column[25]) && !empty($column[25])){
											$corporate_subscription = self::$Subscriptions->where('status',1)->where('type','Corporate')->where('title',trim($column[25]))->first();
											if(isset($corporate_subscription->id)){
												$setData['corporate_subscription_id'] = $corporate_subscription->id;
											}														
										}
										
										$setData['order_value'] = $column[26];
										if(isset($column[27]) && !empty($column[27])){
											$explode_vendors = explode('|',$column[27]);
											$vendor_array = array();
											foreach($explode_vendors as $key => $vendor){
												$vend = self::$User->where('status',1)->where('type','Vendor')->where('name',trim($vendor))->first();
												if(isset($vend->id)){
													$vendor_array[] = $vend->id;
												}														
											}	
											if(count($vendor_array) > 0){
												$vendor_implode = implode(',',$vendor_array);
												$setData['vendor_id'] = $vendor_implode;
											}
										}
										
									}
								}
								//print_r($setData);die;
								$record = self::$CouponCodes->CreateRecord($setData);
							}							
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importBrand
    public function importBrand(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Brands->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							if($column[3] && !empty($column[3])){
								$imgurl = $column[3];
								$url = trim($imgurl);
								if(!empty($url)){
									$destination = base_path().'/public/img/brands/';
									$image_name = $this->uploadImgUrl($url,$destination);
									$setData['image'] = $image_name;
								}
							}
							$record = self::$Brands->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importIngredients
    public function importIngredients(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Ingredients->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							if($column[3] && !empty($column[3])){
								$imgurl = $column[3];
								$url = trim($imgurl);
								if(!empty($url)){
									$destination = base_path().'/public/img/ingredients/';
									$image_name = $this->uploadImgUrl($url,$destination);
									$setData['image'] = $image_name;
								}
							}
							$record = self::$Ingredients->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importLanguage
    public function importLanguage(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Languages->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$record = self::$Languages->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importLanguageProficiency
    public function importLanguageProficiency(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$LanguageProficiencies->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$record = self::$LanguageProficiencies->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importSubCategory
    public function importSubCategory(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						if(isset($column[1]) && !empty($column[1])){
							$explode = explode('|',$column[1]);
							$root_array = array();
							foreach($explode as $key => $explode){								
								$root_cat = self::$Categories->where('title',trim($explode))->first();
								if(isset($root_cat->id)){
									$root_array[] = $root_cat->id;
								}
							}							
							if(count($root_array) > 0){
								$root_categories = implode(',',$root_array);
								$count = self::$SubCategories->where('title',trim($column[2]))->count();
								if($count == 0){
									$setData['category_id'] = $root_categories;
									$setData['title'] = trim($column[2]);
									$setData['slug'] = $this->builtSlug($column[2]);									
									$setData['seo_title'] = $column[3];
									$setData['seo_keywords'] = $column[4];
									$setData['seo_description'] = $column[5];
									$record = self::$SubCategories->CreateRecord($setData);
								}
							}
						}						
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importVendorPlanCategory
    public function importVendorPlanCategory(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$VendorPlanCategories->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$record = self::$VendorPlanCategories->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importVendorPlan
    public function importVendorPlan(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$VendorPlans->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							$record = self::$VendorPlans->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	
	#importAilments
    public function importTax(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						//$count = self::$Ailments->where('title',$column[1])->count();
						//if($count == 0){
							$setData['tax_group'] = $column[1];
							$setData['include_tax'] = $column[2];
							$setData['is_shipping'] = $column[3];
							$record = self::$Taxes->CreateRecord($setData);
						//}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importReview
    public function importReview(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){	
						$count = self::$Reviews->where('title',$column[1])->count();
						if($count == 0){
							$setData['type'] = $column[1];
							$setData['item_id'] = $column[2];
							$setData['rating'] = $column[3];
							$setData['remark'] = $column[4];
							$setData['approved_on'] = $column[5];
							$setData['reject_remark'] = $column[6];
							$setData['approved_by_user_id'] = $request->session()->get('admin_id');
							$record = self::$Reviews->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	
	#importProduct
    public function importProducts(Request $request){
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				set_time_limit(0);
				$file = fopen($fileName, "r");
				$num = 1;
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						$category_ids = $brand_id = $ailment_id = 0;				
						if(isset($column[4]) && !empty($column[4])){
							$categories = json_decode($column[4]);
							$catData = array();
							$root_catid = $sub_cat_id1 = $root_catid2 = 0;
							if(isset($categories[0]->Category)){
								$root = ucwords(trim($categories[0]->Category));
								$category = self::$HealthmugCategories->where('title',$root)->first();
								if(isset($category->id)){
									//$catData[] = $category->id;
									$root_catid = $category->id;
								}else{
									$rootData['title'] = $root;
									$rootData['slug'] = $this->builtSlug($rootData['title']);
									$record1 = self::$HealthmugCategories->CreateRecord($rootData);
									//$catData[] = $record1->id;
									$root_catid = $record1->id;
								}
							}
							if(isset($categories[1]->Category)){
								$sub_cat1 = ucwords(trim($categories[1]->Category));
								$category1 = self::$HealthmugCategories->where('title',$sub_cat1)->first();
								if(isset($category1->id)){
									$catData[] = $category1->id;
									$sub_catid1 = $category1->id;
								}else{
									$cat1Data['title'] = $sub_cat1;
									$cat1Data['parent_id'] = $root_catid;
									$cat1Data['slug'] = $this->builtSlug($cat1Data['title']);
									$record2 = self::$HealthmugCategories->CreateRecord($cat1Data);
									$catData[] = $record2->id;
									$sub_catid1 = $record2->id;
								}
							}
							$category_ids = implode(',',$catData);
						}
						if(isset($column[6]) && !empty($column[6])){
							$images = json_decode($column[6]);
							$imgArray = '';
							$imgArray = array();
							if(count($images) > 0){								
								foreach($images as $key => $img){
									if(isset($img->image_src) && !empty($img->image_src)){
										$url = $img->image_src;
										if(!empty($url)){
											// Image path
											$path_info = pathinfo($url);
											$ext = $path_info['extension'];
											$actual_image_name = time().rand().'.'.$ext;
											$destination = base_path().'/public/img/product/';
											$img = $destination.$actual_image_name;
											// Save image
											file_put_contents($img, file_get_contents($url));
											$imgArray[] = $actual_image_name;
										}
									}
								}				
							}				
						}
						if(isset($column[17]) && !empty($column[17])){
							$brand_title = ucwords(trim($column[17]));
							$brand = self::$Brands->where('title',$brand_title)->first();
							if(isset($brand->id)){
								//$catData[] = $category->id;
								$brand_id = $brand->id;
							}else{
								$brandData['title'] = $brand_title;
								$brandData['slug'] = $this->builtSlug($brandData['title']);
								$brand_data = self::$Brands->CreateRecord($brandData);
								//$catData[] = $record1->id;
								$brand_id = $brand_data->id;
							}							
						}
						if(isset($column[25]) && !empty($column[25])){
							$ailmets_data = json_decode($column[25]);
							if(isset($ailmets_data[0]->Useful)){
								$ailment_title = ucwords(trim($ailmets_data[0]->Useful));
								$ailment = self::$Ailments->where('title',$ailment_title)->first();
								if(isset($ailment->id)){
									//$catData[] = $category->id;
									$ailment_id = $ailment->id;
								}else{
									$ailmentData['title'] = $ailment_title;
									$ailmentData['slug'] = $this->builtSlug($ailment_title);
									$ailment_data = self::$Ailments->CreateRecord($ailmentData);
									//$catData[] = $record1->id;
									$ailment_id = $ailment_data->id;
								}
							}
						}						
						$setData['category_id'] = $root_catid;
						$setData['sub_category_id'] = $category_ids;
						$setData['product_name'] = $column[5];
						$setData['mrp'] = (number_format($column[8],2));
						$setData['list_price'] = (number_format($column[9],2));
						$setData['discounted_price'] = (number_format($column[10],2));
						$setData['product_weight'] = $column[12];
						$setData['brand_id'] = $brand_id;
						$setData['ailment_id'] = $ailment_id;							
						$setData['product_width'] = $column[19];
						$setData['product_height'] = $column[20];
						$setData['product_length'] = $column[18];							
						$setData['short_description'] = '';
						$setData['long_description'] = htmlentities($column[11]);
						$setData['status'] = 1;
						$record = self::$HealthmugProducts->CreateRecord($setData);

						#image URLs
						if($record->id && !empty($record->id)){
							$i = 1;
							$lastOrdering = self::$HealthmugProductImages->where('product_id',$record->id)->orderBy('img_ordering', 'DESC')->first();
							if(isset($lastOrdering->id)){
								$i = $lastOrdering->img_ordering + 1;
							}
							if(isset($imgArray) && count($imgArray) > 0){
								foreach($imgArray as $img){
									$url = trim($img);
									if(!empty($img)){
										$setImgData['product_id'] = $record->id;
										$setImgData['image'] = $img;
										$setImgData['img_title'] = $column[5];
										$setImgData['img_alt_title'] = $column[5];
										$setImgData['status'] = 1;
										$setImgData['img_ordering'] = $i;
										self::$HealthmugProductImages->CreateRecord($setImgData);
										$i++;
									}
								}
							}
						}						
						if(isset($column[13]) && !empty($column[13])){
							if($record->id && !empty($record->id)){
								$faqs = json_decode($column[13]);
								if(isset($faqs) && count($faqs) > 0){
									foreach($faqs as $key => $faq){
										if(isset($faq->Question) && !empty($faq->Question)){
											$Question = $faq->Question;
											if(!empty($Question)){
												$setImgData['product_id'] = $record->id;
												$setImgData['question'] = $Question;
												$Answers = json_decode($column[14]);
												if(isset($Answers[0]->Answer) && !empty($Answers[0]->Answer)){
													$answer = $Answers[0]->Answer;
												}else{
													$answer = '';
												}
												$setImgData['answer'] = $answer;
												$setImgData['status'] = 2;
												self::$HealthmugProductFaqs->CreateRecord($setImgData);																						
											}
										}
									}	
								}
							}
						}						
						if(isset($column[21]) && !empty($column[21])){
							if($record->id && !empty($record->id)){
								$reviews = json_decode($column[21]);
								if(isset($reviews) && count($reviews) > 0){
									foreach($reviews as $key => $review){
										if(isset($review->Review_text) && !empty($review->Review_text)){
											$Review_text = $review->Review_text;
											if(!empty($review->Review_text)){
												$setImgReview['item_id'] = $record->id;
												$setImgReview['remark'] = $Review_text;
												$Reviewer_names = json_decode($column[22]);
												if(isset($Reviewer_names[0]->Reviewer_name) && !empty($Reviewer_names[0]->Reviewer_name)){
													$Reviewer_name = $Reviewer_names[0]->Reviewer_name;
												}else{
													$Reviewer_name = '';
												}
												$Review_Hadlines = json_decode($column[23]);
												if(isset($Review_Hadlines[0]->Review_Hadline) && !empty($Review_Hadlines[0]->Review_Hadline)){
													$Review_Hadline = $Review_Hadlines[0]->Review_Hadline;
												}else{
													$Review_Hadline = '';
												}
												$Dates = json_decode($column[24]);
												if(isset($Dates[0]->Date) && !empty($Dates[0]->Date)){
													$Date = $Dates[0]->Date;
												}else{
													$Date = '';
												}
												$setImgReview['username'] = $Reviewer_name;
												$setImgReview['heading'] = $Review_Hadline;
												$setImgReview['approved_on'] = $Date;
												$setImgReview['rating'] = $column[15];
												$setImgReview['status'] = 2;
												self::$HealthmugProductReviews->CreateRecord($setImgReview);																						
											}
										}
									}	
								}
							}
						}				
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importSymptoms
    public function importSymptoms(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				$error = array();
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						$count = self::$Symptoms->where('title',$column[1])->count();
						if($count == 0){
							$setData['title'] = $column[1];
							$setData['description'] = $column[2];
							if(isset($column[3]) && !empty($column[3])){								
								$ailments_array = array();
								$ailments_ids_arr = explode('|',$column[3]);
								foreach($ailments_ids_arr as $key => $ailment_title){
									$ailment = self::$Ailments->where('status',1)->where('title',trim($ailment_title))->first();
									if(isset($ailment->id)){
										$ailments_array[] = $ailment->id;
									}else{
										$error['ailment_id'][] = trim($ailment_title);	
									}
								}							
								$setData['ailment_id'] = trim(implode(',',$ailments_array));									
							}
							if(isset($column[4]) && !empty($column[4])){								
								$specialties_array = array();
								$specialties_ids_arr = explode('|',$column[4]);
								foreach($specialties_ids_arr as $key => $specialty){
									$specilities = self::$Specialties->where('title',trim($specialty))->first();
									if(isset($specilities->id)){
										$specialties_array[] = $specilities->id;
									}else{
										$error['speciality_id'][] = trim($specialty);	
									}
								}							
								$setData['speciality_id'] = trim(implode(',',$specialties_array));									
							}
							if(isset($column[5]) && !empty($column[5])){								
								$symptoms_array = array();
								$symptoms_ids_arr = explode('|',$column[5]);
								foreach($symptoms_ids_arr as $key => $symptom){
									$symptom_data = self::$Symptoms->where('title',trim($symptom))->first();
									if(isset($symptom_data->id)){
										$symptoms_array[] = $symptom_data->id;
									}else{
										//$error['symptom_id'][] = trim($symptom);	
									}
								}							
								$setData['symptom_id'] = trim(implode(',',$symptoms_array));									
							}
							$setData['questions'] = $column[6];
							if(count($error) == 0){
								$record = self::$Symptoms->CreateRecord($setData);
							}
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	#importSymptoms
    public function importStatus(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				$error = array();
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						$count = self::$Status->where('title',$column[1])->count();
						if($count == 0){
							$type = array('Order','Vendor');
							$setData['title'] = $column[1];
							if(in_array($column[2],$type)){
								$setData['type'] = $column[2];	
							}else{
								$error['type'] = $column[2];
							}
							if(count($error) == 0){
								$record = self::$Status->CreateRecord($setData);
							}
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }
	
	#importCustomer
    public function importCustomer(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				$error = array();
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						$gender_array = array('Male','Female','Other');
						$count = self::$User->where('email',trim($column[2]))->count();
						if($count == 0){
							$setData['type'] = 'User';
							$setData['name'] = $column[1];
							$setData['email'] = $column[2];							
							$setData['mobile'] = $column[3];
							if(in_array($column[4],$gender_array)){
								$setData['gender'] = $column[4];	
							}else{
								$error['gender'] = $column[4];
							}
							$pass = $column[5];
							if(empty(trim($column[5]))){
								$pass = '1234';	
							}
							$password = password_hash(trim($pass),PASSWORD_BCRYPT);
                    		$setData['password'] = $password;
							
							if(count($error) == 0){
								$record = self::$User->CreateRecord($setData);
							}
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}
    }	

	#importNewsletter
    public function importNewsletter(Request $request){
		
		if(!$request->session()->has('admin_email')){ echo 'SessionExpired'; die; }
		
		$fileName = $_FILES["file"]["tmp_name"];
		if(isset($fileName) && !empty($fileName)){
			$csvMimes = array('application/csv', 'text/csv');
			if(!empty($_FILES['file']['name']) && $_FILES["file"]["size"] > 0 && in_array($_FILES['file']['type'], $csvMimes)){
				$file = fopen($fileName, "r");
				$num = 1;
				$error = array();
				while(($column = fgetcsv($file, 10000, ",")) !== FALSE){
					if($num > 1){
						$count = self::$Newsletter->where('email',trim($column[1]))->count();
						if($count == 0){
							$setData['email'] = $column[1];
							$record = self::$Newsletter->CreateRecord($setData);
						}
					}
					$num++;
				}
				echo 'Success'; die;
			}else{
				echo 'InvalidFileType'; die;
			}
		}else{
			echo 'ChoseFile'; die;
		}

    }		
	
}