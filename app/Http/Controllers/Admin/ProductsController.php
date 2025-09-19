<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Products;

use App\Models\ProductImages;

use App\Models\Categories;

use App\Models\SubCategories;

use App\RouteHelper;

use App\Models\TokenHelper;

use App\Models\Responses;

use ReallySimpleJWT\Token;

use Illuminate\Support\Str;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use Session;

use Validator;

use Mail;

use URL;

use Cookie;

use Illuminate\Validation\Rule;



class ProductsController extends Controller{

	private static $Category;

	private static $SubCategories;
	
	private static $ProductsModel;

	private static $ProductImagesModel;

		

	public function __construct(){

		self::$Category = new Categories();

		self::$SubCategories = new SubCategories();

		self::$ProductsModel = new Products();

		self::$ProductImagesModel = new ProductImages();

	}



    #admin dashboard page

    public function getList(Request $request,$vendor=NULL,$month=NULL,$year=NULL){

		if(!$request->session()->has('admin_email')){return redirect('/admin/');}		       

		$admin_type = $request->session()->get('admin_type');

		$admin_id = $request->session()->get('admin_id');

        return view('/admin/products/index',compact(array('admin_type','admin_id')));

    }

	#admin 

    public function listPaginate(Request $request){

		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

		$admin_type = $request->session()->get('admin_type');

		$admin_id = $request->session()->get('admin_id');		

        $query = self::$ProductsModel->where('products.status', '!=', 3);

		//$query->where('products.id', '!=', 3);



		if($request->input('title')  && $request->input('title') != ""){

            $SearchKeyword = $request->input('title');

            $query->where(function($query) use ($SearchKeyword){

                if(!empty($SearchKeyword)){

                    $query->where('products.title', 'like', '%'.$SearchKeyword.'%');

                }

             });

		}		

		$records =  $query->orderBy('products.id', 'DESC')->simplePaginate(100);

		$admin_type = $request->session()->get('admin_type');

		$admin_id = $request->session()->get('admin_id');

        return view('/admin/products/paginate',compact('records','admin_type','admin_id'));

    }



    #add new Product

    public function addPage(Request $request){

		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

		if($request->input()){

			$validator = Validator::make($request->all(), [

                'title' => 'required',

				'category_id' => 'required',

				'sub_category_id' => 'required',

            ],[

                'title.required' => 'Please enter product name.',

				'category_id.required' => 'Please select category.',

				'sub_category_id.required' => 'Please select sub category.',

            ]);

			if($validator->fails()){

				$errors = $validator->errors();

				if($errors->first('title')){

                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('title')));die;

				}

				if($errors->first('category_id')){

                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('category_id')));die;

				}

				if($errors->first('sub_category_id')){

                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('sub_category_id')));die;

				}

			}else{

                if(!self::$ProductsModel->ExistingRecord($request->input('title'))){

					$root_cat = $this->getRootCategory($request->input('category_id'));
					
					$sub_cat = $this->getSubCategory($request->input('sub_category_id'));

                    $setData['category_id'] = $request->input('category_id');
					$setData['is_b2b'] = $request->input('is_b2b');
					$setData['sub_category_id'] = $request->input('sub_category_id');

					$setData['title'] = $request->input('title');
					$setData['type'] = $request->input('type');
					$setData['terrain'] = $request->input('terrain');
					$setData['location'] = $request->input('location');

                    $setData['slug'] = Str::slug($sub_cat->title.' '.$root_cat->title.' '.$request->input('title'));

                   // $setData['price'] = $request->input('price');

					//$setData['quantity'] = $request->input('quantity');
					
					$setData['address'] = $request->input('address');
					$setData['city'] = $request->input('city');
					$setData['state'] = $request->input('state');
					$setData['country'] = $request->input('country');

                    $setData['description'] = $request->input('description');

					$setData['seo_title'] = $request->input('seo_title');

					$setData['seo_description'] = $request->input('seo_description');

					$setData['seo_keyword'] = $request->input('seo_keyword');

					$setData['robot_tags'] = $request->input('robot_tags');

					if(isset($request->image) && $request->image->extension() != ""){

                        $validator = Validator::make($request->all(), [

                            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

                        ]);

                        if($validator->fails()){

                            $errors = $validator->errors();

                            return json_encode(array('heading'=>'Error','msg'=>$errors->first('image')));die;

                        }else{

							$actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('image')).'.'.$request->image->extension());

                            $destination = base_path().'/public/img/products/';

                            $request->image->move($destination, $actual_image_name);

                            $setData['image'] = $actual_image_name;

                        }

                    }

                    $record = self::$ProductsModel->CreateRecord($setData);	
					
					if(empty($record->sku)){

						$sku = $this->productCode($request->input('title'), $record->id);
					
						$setData1['id'] = $record->id;

						$setData1['sku'] = $sku;

						self::$ProductsModel->UpdateRecord($setData1);

					}

					echo json_encode(array('heading'=>'Success','page'=>$request->input('page_path'),'msg'=>'Product added successfully','recordID' => base64_encode($record->id)));die;

                }else{

					echo json_encode(array('heading'=>'Error','page'=>$request->input('page_path'),'msg'=>'Product already exists'));die;	

				}                

			}

		}

		$admin_type = $request->session()->get('admin_type');

		$admin_id = $request->session()->get('admin_id');

		$category_list = $this->getCategories();

		return view('/admin/products/add-page',compact('admin_id','admin_type','category_list'));

    }



    #edit Product

    public function editPage(Request $request, $row_id){

		$RowID =  base64_decode($row_id);

		$rowData = self::$ProductsModel->where(array('id' => $RowID))->first();	

		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

        if($request->input()){

			$validator = Validator::make($request->all(), [

                'title' => 'required',

				'category_id' => 'required',

				'sub_category_id' => 'required',

            ],[

                'title.required' => 'Please enter product name.',

				'category_id.required' => 'Please select category.',

				'sub_category_id.required' => 'Please select sub category.',

            ]);

			if($validator->fails()){

				$errors = $validator->errors();

				if($errors->first('title')){

                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('title')));die;

				}

				if($errors->first('category_id')){

                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('category_id')));die;

				}

				if($errors->first('sub_category_id')){

                    return json_encode(array('heading'=>'Error','msg'=>$errors->first('sub_category_id')));die;

				}

			}else{

                //profile image

                if(self::$ProductsModel->ExistingRecordUpdate($request->input('title'), $RowID)){

                    echo json_encode(array('heading'=>'Error','msg'=>'Product already exists.'));die;

                }else{
					$heroSectionBanner = $request->input('old_hero_section_banner');
					if(isset($request->hero_section_banner) && $request->hero_section_banner->extension() != ""){
						
						$actual_image_name1 = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('image')).'.'.$request->hero_section_banner->extension());

                            $destination = base_path().'/public/img/products/';

                            $request->hero_section_banner->move($destination, $actual_image_name1);

                            $heroSectionBanner = $actual_image_name1;

                            if($request->input('old_hero_section_banner') != ""){

                                if(file_exists($destination.$request->input('old_hero_section_banner'))){

                                    unlink($destination.$request->input('old_hero_section_banner'));

                                }

                            }
					}

					$root_cat = $this->getRootCategory($request->input('category_id'));
					
					$sub_cat = $this->getSubCategory($request->input('sub_category_id'));

                    $setData['id'] =  $RowID;

					$setData['is_b2b'] = $request->input('is_b2b');
					$setData['category_id'] = $request->input('category_id');
					$setData['sub_category_id'] = $request->input('sub_category_id');	
                    $setData['title'] = $request->input('title');
					$setData['type'] = $request->input('type');
					$setData['terrain'] = $request->input('terrain');
					$setData['location'] = $request->input('location');

                    $setData['slug'] = Str::slug($sub_cat->title.' '.$root_cat->title.' '.$request->input('title'));

                    //$setData['price'] = $request->input('price');

					//$setData['quantity'] = $request->input('quantity');
					
					$setData['address'] = $request->input('address');
					$setData['city'] = $request->input('city');
					$setData['state'] = $request->input('state');
					$setData['country'] = $request->input('country');
					$setData['hero_section_heading'] = $request->input('hero_section_heading');
					$setData['hero_section_description'] = $request->input('hero_section_description');
					$setData['hero_section_banner'] = $heroSectionBanner;
					$setData['upper_heading'] = ucwords(strtolower($request->input('upper_heading')));
					$setData['upper_description'] = $request->input('upper_description');
					$setData['description'] = $request->input('description');
					$setData['seo_title'] = $request->input('seo_title');
					$setData['seo_description'] = $request->input('seo_description');
					$setData['seo_keyword'] = $request->input('seo_keyword');
					$setData['robot_tags'] = $request->input('robot_tags');
					
					$setData['parking_facility'] = $request->input('parking_facility');
					$setData['souvenir_shop'] = $request->input('souvenir_shop');
					$setData['laundry_service'] = $request->input('laundry_service');
					$setData['conference_hall'] = $request->input('conference_hall');
					$setData['travel_desk'] = $request->input('travel_desk');
					$setData['wifi'] = $request->input('wifi');
					$setData['doctor_on_call'] = $request->input('doctor_on_call');

					if(empty($rowData->sku)){

						$sku = $this->productCode($request->input('title'), $rowData->id);
					
						$setData1['id'] = $rowData->id;

						$setData1['sku'] = $sku;

						self::$ProductsModel->UpdateRecord($setData1);

					}				


					if(isset($request->image) && $request->image->extension() != ""){

                        $validator = Validator::make($request->all(), [

                            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

                        ]);

                        if($validator->fails()){

                            $errors = $validator->errors();

                            return json_encode(array('heading'=>'Error','msg'=>$errors->first('image')));die;

                        }else{

							$actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('image')).'.'.$request->image->extension());

                            $destination = base_path().'/public/img/products/';

                            $request->image->move($destination, $actual_image_name);

                            $setData['banner'] = $actual_image_name;

                            if($request->input('old_image') != ""){

                                if(file_exists($destination.$request->input('old_image'))){

                                    unlink($destination.$request->input('old_image'));

                                }

                            }

                        }

                    }					

					self::$ProductsModel->UpdateRecord($setData);					

               	}

                echo json_encode(array('heading'=>'Success','page'=>$request->input('page_path'),'msg'=>'Product information updated successfully','recordID' => base64_encode($RowID)));die;

			}

		}

		$admin_type = $request->session()->get('admin_type');

		$admin_id = $request->session()->get('admin_id');

        if(isset($rowData->id)){

			$category_list = $this->getCategories();

			$product_images = self::$ProductImagesModel->where('status','!=',3)->where('product_id',$rowData->id)->latest()->get();

            return view('/admin/products/edit-page',compact('rowData','row_id','category_list','product_images'));

        }else{

            return redirect('/admin/products');

        }

    }
	

	############ update produt title ###############

	public function updateProductTitle(Request $request){

		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

        if($request->input()){

			$setData['id'] = $request->input('id');

			$setData[$request->input('field')] = $request->input('value');			

			self::$ProductImagesModel->UpdateRecord($setData);

			echo "Success";die;

		}	

	}


	public function updateProductFile(Request $request){

		if(!$request->session()->has('admin_email')){return redirect('/admin/');}

		$msg['msg'] = 'Error';

		if(isset($request->file) && $request->file->extension() != ""){

			$validator = Validator::make($request->all(), [

				'file' => 'required|image|mimes:jpeg,png,jpg,webp,svg|max:20480'

			]);

			if($validator->fails()){

				$errors = $validator->errors();

				return json_encode(array('heading'=>'Error','msg'=>$errors->first('file')));die;

			}else{

				$actual_image_name = strtolower(sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(rand().true).$request->file('file')).'.'.$request->file->extension());

				$destination = base_path().'/public/img/products/';

				$request->file->move($destination, $actual_image_name);

				$setData['image'] = $actual_image_name;

				if($request->input('old_image') != ""){

					if(file_exists($destination.$request->input('old_image'))){

						unlink($destination.$request->input('old_image'));

					}

				}

				$setData['id'] = $request->input('id');

				self::$ProductImagesModel->UpdateRecord($setData);

				$msg['msg'] = 'Success';

			}

		}

		echo json_encode(array('data'=>$msg));die;

	}



    /************** uploadProductImages ************/

	public function uploadProductImages(Request $request){

        //profile image

		if(!empty($_FILES)){
			$postData = $request->all();
			$msg = "Error";
			$fileName = $_FILES['file']['name']; //Get the image
			$file_temp_name = $_FILES['file']['tmp_name'];
			$pathInfo = pathinfo(basename($fileName));
			$ext = $request->file->extension();
			$checkImage = getimagesize($file_temp_name);
			$actual_image_name = sha1(str_shuffle(microtime(true).mt_rand(100001,999999)).uniqid(mt_rand().true).$request->file('file')).'.'.$request->file->extension();
			$destination2 = base_path().'/public/img/products/';
			if($checkImage !== false){
				if($request->file->move($destination2, $actual_image_name)){						
					$setData['product_id'] = $request->input('product_id');
					$setData['image'] = $actual_image_name;
					$record = self::$ProductImagesModel->CreateRecord($setData);
					
					$msg = "Success";
				}
			}
		}
		echo json_encode(array('msg' => $msg));

	}
	

	public function getCategories(){

		return self::$Category->where('status',1)->pluck('title','id');

	}

	public function getRootCategory($root_id){
        return self::$Category->where('status', '!=', 3)->where('id',$root_id)->first();
    }

	public function getSubCategory($cat_id){
        return self::$SubCategories->where('status', '!=', 3)->where('id',$cat_id)->first();
    }

}