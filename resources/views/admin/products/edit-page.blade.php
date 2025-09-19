@extends('layout.admin.dashboard')

@section('content')

<link href="{{ URL::asset('public/admin/css/dropzone.css') }}" rel="stylesheet">
<script src="{{ URL::asset('public/admin/js/dropzone.js') }}"></script>

<div class="page-heading">

  <div class="page-title">

    <div class="row">

      <div class="col-12 col-md-6 order-md-1 order-last">

        <h3>Edit Hotel</h3>

      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">

        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">

          <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>

            <li class="breadcrumb-item"><a href="{{url('/admin/products')}}">Hotels</a></li>

            <li class="breadcrumb-item active" aria-current="page">Edit Hotel</li>

          </ol>

        </nav>

      </div>

    </div>

  </div>

  <section class="section">

    <form class="form w-100 productForm" id="pageForm" action="#">

      <div class="row">

        <div class="col-9 col-md-9">

        	<div class="card">

        	<div class="card-body">

              <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item" role="presentation"> <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"
            
            role="tab" aria-controls="home" aria-selected="true">General Info</a> </li>
            
            <li class="nav-item" role="gallery"> <a class="nav-link" id="gallery-tab" data-bs-toggle="tab" href="#gallery"
            
            role="tab" aria-controls="gallery" aria-selected="false">Property Images</a> </li>
            
            <li class="nav-item" role="presentation"> <a class="nav-link" id="seo-tab" data-bs-toggle="tab" href="#seo"
            
            role="tab" aria-controls="seo" aria-selected="false">SEO Info</a> </li>
            
            <li class="nav-item" role="presentation"> <a class="nav-link" id="services-tab" data-bs-toggle="tab" href="#services"
            
            role="tab" aria-controls="services" aria-selected="false">Services</a> </li>

                

              </ul>

              <div class="tab-content mt-5" id="myTabContent replaceHtml">

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                  <div class="row">
                  
                  <div class="col-md-2">

                      <div class="form-group">

                        <label for="basicInput">B2B</label>

                        <select class="form-select" name="is_b2b" id="is_b2b">

                          <option @if($rowData->is_b2b == 1) selected @endif value="1">Yes</option>
                          <option @if($rowData->is_b2b == 2) selected @endif value="2">No</option>

                        </select>

                      </div>

                    </div>
                    
                    <div class="col-md-2">

                      <div class="form-group">

                        <label for="basicInput">Type</label>

                        <select class="form-select" name="type" id="type">

                          <option @if($rowData->type == 'Domestic') selected @endif value="Domestic">Domestic</option>
                          <option @if($rowData->type == 'International') selected @endif value="International">International</option>

                        </select>

                      </div>

                    </div>

                  	<div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Category</label>

                        <select class="form-select" name="category_id" id="category_id" onchange="getSubCategory(this.value)">

                        	<option value="">Select Category</option>

                            @foreach($category_list as $key => $category)

                            	<option {{$rowData->category_id == $key?'selected':''}} value="{{$key}}">{{$category}}</option>

                            @endforeach

                        </select>

                      </div>

                    </div>

                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Sub Category</label>

                        <select class="form-select" name="sub_category_id" id="sub_category_id">

                          <option value="">Select Sub Category</option>

                          @php
                            $sub_catlist = Helper::getSubCategoryList($rowData->category_id);
                          @endphp

                          @foreach($sub_catlist as $key => $sub_cat)

                            <option {{$rowData->sub_category_id == $key?'selected':''}} value="{{$key}}">{{$sub_cat}}</option>

                          @endforeach

                        </select>

                      </div>

                    </div>
                    
                    

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">Title</label>

                        <input type="text" class="form-control" placeholder="Enter Title" value="{{$rowData->title}}" name="title" id="title">

                      </div>

                    </div>
                    
                    <div class="col-md-3">

                      <div class="form-group">

                        <label for="basicInput">Location</label>

                        <select class="form-select" name="location" id="location">
                            <option value="">Select</option>
                            <option @if($rowData->location == 'North') selected @endif value="North">North</option>
                            <option @if($rowData->location == 'West') selected @endif value="West">West</option>
                            <option @if($rowData->location == 'South') selected @endif value="South">South</option>
                            <option @if($rowData->location == 'East') selected @endif value="East">East</option>

                        </select>

                      </div>

                    </div>
                    
                    <div class="col-md-3">

                      <div class="form-group">

                        <label for="basicInput">Terrain</label>

                        <select class="form-select" name="terrain" id="terrain">

                            <option value="">Search</option>
                            <option @if($rowData->terrain == 'Beach') selected @endif value="Beach">Beach</option>
                            <option @if($rowData->terrain == 'City') selected @endif value="City">City</option>
                            <option @if($rowData->terrain == 'Desert') selected @endif value="Desert">Desert</option>
                            <option @if($rowData->terrain == 'Hill station') selected @endif value="Hill station">Hill station</option>
                            <option @if($rowData->terrain == 'Himalayan') selected @endif value="Himalayan">Himalayan</option>
                            <option @if($rowData->terrain == 'Jungle') selected @endif value="Jungle">Jungle</option>
                            <option @if($rowData->terrain == 'Waterfront') selected @endif value="Waterfront">Waterfront</option>

                        </select>

                      </div>

                    </div>
                    
                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Address</label>

                        <input type="text" class="form-control" value="{{$rowData->address}}" name="address" id="address">

                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">City</label>

                        <input type="text" class="form-control" value="{{$rowData->city}}" name="city" id="city">

                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">State</label>

                        <input type="text" class="form-control" value="{{$rowData->state}}" name="state" id="state">

                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Country</label>

                        <input type="text" class="form-control" name="country" value="{{$rowData->country}}" id="country">

                      </div>

                    </div>
                    
                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Hero Section Heading</label>

                        <input type="text" class="form-control" name="hero_section_heading" value="{{$rowData->hero_section_heading}}" id="hero_section_heading">

                      </div>

                    </div>
                    
                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Hero Section Description</label>

                        <textarea class="form-control" name="hero_section_description" id="hero_section_description">{{$rowData->hero_section_description}}</textarea>

                      </div>

                    </div>
                    
                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Upper Heading</label>

                        <input type="text" class="form-control" name="upper_heading" value="{{$rowData->upper_heading}}" id="upper_heading">

                      </div>

                    </div>
                    
                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Upper Description</label>

                        <textarea class="form-control" name="upper_description" id="upper_description">{{$rowData->upper_description}}</textarea>

                      </div>

                    </div>

                    <?php /* ?><div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">Price</label>

                        <input type="text" class="form-control numberonly" maxlength="5" placeholder="Enter Price" value="{!! $rowData->price !!}" name="price" id="price">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">Quantity</label>

                        <input type="text" class="form-control numberonly" placeholder="Enter Quantity" maxlength="4" value="{!! $rowData->quantity !!}" name="quantity" id="quantity">

                      </div>

                    </div><?php */ ?>

                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Description</label>

                        <textarea class="form-control editorBox" placeholder="Enter Description" name="description" id="description">{!! $rowData->description !!}</textarea>

                      </div>

                    </div>                    

                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Banner</label>

                        <input type="file" class="form-control" name="image" id="image" accept="image/*">

                        <input type="hidden" name="old_image" value="{!! $rowData->banner !!}" />

                      </div>

                    </div>                     

                    @if($rowData->banner != "")

                    <div class="col-md-8">

                      <div class="form-group">

                        <label for="basicInput">&nbsp;</label>

                        <img src="{{URL::asset('public/img/products/')}}/{!! $rowData->banner !!}" style="max-width: 150px;height: auto;"> 

                      </div>

                    </div>

                    @endif
                    
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Hero Section Banner</label>

                        <input type="file" class="form-control" name="hero_section_banner" id="hero_section_banner" accept="image/*">

                        <input type="hidden" name="old_hero_section_banner" value="{!! $rowData->hero_section_banner !!}" />

                      </div>

                    </div>                     

                    @if($rowData->hero_section_banner != "")

                    <div class="col-md-8">

                      <div class="form-group">

                        <label for="basicInput">&nbsp;</label>

                        <img src="{{URL::asset('public/img/products/')}}/{!! $rowData->hero_section_banner !!}" style="max-width: 150px;height: auto;"> 

                      </div>

                    </div>

                    @endif

                    </div>                     

                </div>


                <div class="tab-pane fade" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">

                  <div class="row">

                    <div class="col-md-12">
                    <form class="form w-100" id="pageForm" action="#">
                      <div class="row">
                          <div class="col-12 col-md-12">
                              <div class="card">
                                  <div class="card-body"> 
                                      <div class="row">
                                          <div class="col-12">
                                            <label for="basicInput">Hotel Images</label>
                                              <div id="my-awesome-dropzone" class="dropzone"></div>
                                          </div>
                                          @if(isset($product_images) && count($product_images) > 0)
                                          <div class="col-12"><label for="basicInput">Product Images</label></div>
                                          <div class="col-12" id="replaceHtml">
                                              <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                              <thead>
                                                <tr>
                                                  <th>#</th>
                                                  <th>Image</th>
                                                  <th>Status</th>
                                                  <th>Action</th>
                                                  <th>Created</th> 
                                                </tr>
                                              </thead>
                                              <tbody>
                                              @foreach($product_images as $key => $value)
                                              <tr>
                                                <td>{{ $key+1; }}</td>
                                                <td>
                                                  <a target="_blank" href="{{ URL::asset('public/img/products/') }}/{!! $value->image !!}" data-sub-html="Image"> 
                                                  <img width="100px" class="img-responsive" src="{{ URL::asset('public/img/products/') }}/{!! $value->image !!}"> 
                                                  </a>
                                                </td>
                                                <td> 
                                                  @if($value->status == 1)
                                                  <a href="javascript:void(0);" onclick="changeStatus('product_images','{!!$value->id!!}','{!!$value->status!!}');" class="badge bg-success ">Active</a>
                                                  @else
                                                  <a href="javascript:void(0);" onclick="changeStatus('product_images','{!!$value->id!!}','{!!$value->status!!}');"  class="badge bg-danger">In-Active</a>
                                                  @endif
                                                </td>
                                                <td><a href="javascript:void(0);" onclick="deleteImgData('product_images','{{ $value->id }}');" class="btn btn-sm btn-danger"  title="Delete">
                                                      <i class="bi bi-trash"></i>
                                                  </a></td>
                                                <td>{{ date("F jS, Y h:i A",strtotime($value->created_at)); }}</td> 
                                              </tr>
                                              @endforeach
                                              </tbody>
                                            </table>
                                          </div>
                                          @endif                                          
                                      </div>                        
                                  </div>                    
                              </div>
                          </div>            
                      </div>
                      </form>

                    </div>

                  </div>

                </div>
                

                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">SEO Title</label>

                        <input type="text" class="form-control" placeholder="Enter SEO Title" value="{!! $rowData->seo_title !!}" name="seo_title" id="seo_title">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">SEO Description</label>

                        <textarea class="form-control" rows="6" placeholder="Enter SEO Description" name="seo_description" id="seo_description">{!! $rowData->seo_description !!}</textarea>

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">SEO Keywords</label>

                        <textarea class="form-control" rows="6" placeholder="Enter SEO Keywords" name="seo_keyword" id="seo_keyword">{!! $rowData->seo_keyword !!}</textarea>

                      </div>

                    </div>

                    <div class="col-md-6">

                    	<div class="form-group">

                        	<label for="basicInput">SEO Robots</label>

                        	<select id="robot_tags" name="robot_tags" value="index,nofollow" class="form-select">

                                <option {{ $rowData->robot_tags == 'index,follow'?'selected':'' }} value="index,follow">index,follow</option>

                                <option {{ $rowData->robot_tags == 'index,nofollow'?'selected':'' }} value="index,nofollow">index,nofollow</option>

                                <option {{ $rowData->robot_tags == 'noindex,follow'?'selected':'' }} value="noindex,follow">noindex,follow</option>

                                <option {{ $rowData->robot_tags == 'noindex,nofollow'?'selected':'' }} value="noindex,nofollow">noindex,nofollow</option>

                        	</select>

                        </div>

                    </div>

                  </div>

                </div>
                
                <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="services-tab">

                  <div class="row">

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Parking Facility</label>
                        <select class="form-select" name="parking_facility" id="parking_facility">
                        <option @if($rowData->parking_facility == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->parking_facility == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Souvenir Shop</label>
                        <select class="form-select" name="souvenir_shop" id="souvenir_shop">
                        <option @if($rowData->souvenir_shop == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->souvenir_shop == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Laundry Service</label>
                        <select class="form-select" name="laundry_service" id="laundry_service">
                        <option @if($rowData->laundry_service == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->laundry_service == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Conference Hall</label>
                        <select class="form-select" name="conference_hall" id="conference_hall">
                        <option @if($rowData->conference_hall == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->conference_hall == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Travel Desk</label>
                        <select class="form-select" name="travel_desk" id="travel_desk">
                        <option @if($rowData->travel_desk == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->travel_desk == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Wifi</label>
                        <select class="form-select" name="wifi" id="wifi">
                        <option @if($rowData->wifi == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->wifi == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>
                    
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput">Doctor on Call</label>
                        <select class="form-select" name="doctor_on_call" id="doctor_on_call">
                        <option @if($rowData->doctor_on_call == 1) selected @endif value="1">Yes</option>
                        <option @if($rowData->doctor_on_call == 2) selected @endif value="2">No</option>
                        </select>
                      </div>
                    </div>

                    

                  </div>

                </div>                

              </div>                             

            </div>

            </div>

        </div>

        <div class="col-3 col-md-3 ">

          <div class="card">

            <div class="col-md-12">

              <div class="text-left  p-3 p-l-20"> 

                <!--begin::Submit button-->

                <button type="button" id="form_submit" class="btn btn-sm btn-primary fw-bolder me-3 my-2"> <span class="indicator-label" id="formSubmit">Submit</span> <span class="indicator-progress d-none">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span> </span> </button>

                <!--end::Submit button--> 

              </div>

            </div>

          </div>

        </div>

      </div> 

    </form>

  </section>

</div>

<!-- end plugin js --> 

<script type="text/javascript">

function deleteImgData(table,rowID){
	if(table != "" && rowID != ""){
        swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this record!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: "{{url('admin/delete-record')}}",
                data: {table:table,rowID:rowID},
                success: function(msg){
                    if(msg == "Success"){
                        swal({
                          title: 'Success',
                          text: 'Record has been deleted successfully.',
                          type: 'success',
                          confirmButtonText: 'Ok',
                          confirmButtonColor: "#009EF7"
                        });                        
                        filterData('simple');
                    }else{
                        swal({
                            title: "Oops!",
                            text: msg,
                            type: "warning",
                            timer: 3000
                        });
                    }
                }
            });
        } else {
            swal("Your record is safe!");
        }
        });
	}else{
		return false;
	}
}
  
  function filterData(type = null){
    
    window.location.href = '';

  }

	$('#my-awesome-dropzone').attr('class', 'dropzone');
	var myDropzone = new Dropzone('#my-awesome-dropzone', {
		url: "{{url('admin/upload-product-images')}}",
		clickable: true,
		method: 'POST',
		maxFiles: 50,
		parallelUploads: 50,
		maxFilesize: 20,
		addRemoveLinks: false,
		dictRemoveFile: 'Remove',
		dictCancelUpload: 'Cancel',
		dictCancelUploadConfirmation: 'Confirm cancel?',
		dictDefaultMessage: 'Drop files here to upload',
		dictFallbackMessage: 'Your browser does not support drag n drop file uploads',
		dictFallbackText: 'Please use the fallback form below to upload your files like in the olden days',
		paramName: 'file',
		forceFallback: false,
		createImageThumbnails: true,
		maxThumbnailFilesize: 5,
    params: {'product_id':'{{$rowData->id}}'},
		//acceptedFiles: ".jpeg,.jpg,.webp,.png,.svg",
		acceptedFiles: "image/*",
		autoProcessQueue: true,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		init: function() {
			this.on('thumbnail', function(file) {
				if (file.width < 50 || file.height < 50) {
					//file.rejectDimensions();
					file.acceptDimensions();
				} else {
					file.acceptDimensions();
				}
			});
		},
		accept: function(file, done) {
			file.acceptDimensions = done;
			file.rejectDimensions = function() {
				done('The image must be at least 50 x 50px')
			};
		}
	});
	
	myDropzone.on("complete", function(file) {
		var status = file.status;
		if (status == 'success') {
	
		}
		console.log(file);
	});
	
	var count = 1;
	myDropzone.on("success", function(file, responseText) {
		var fnamenew = file.name;
		var fname = fnamenew.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '');
		$("#productsimgall").append('<input type="hidden" name="image[]" class="img_eng" id="img_eng' + fname + '" value="' + responseText + '">');
	   
		count++;
	});
	
	myDropzone.on("removedfile", function(file) {
		var fname = file.name;
		fname2 = fname.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');    
		var image = $('#img_eng'+fname2).val();
		
	});
	
	myDropzone.on("addedfile", function(file) {
		
	});
</script>

<script type="text/javascript"> 


function getSubCategory(category_id){
    $('#sub_category_id').html('<option value="">Select Sub Category</option>');
    if(category_id != ''){

      $.ajax({

          url: "{{url('admin/get-sub-category')}}",

          data: {category_id:category_id},

          type: 'POST',

          headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

          },

          success:function(response){

            $('#sub_category_id').html(response);

          },

          error: function(ts){

              console.log(ts);

              swal("Error!", 'Something went wrong, please try after sometime.', "error");

              return false;

          }

      });
      return false;
    }
  }

	

    $(document).ready(function(){

		$('.numberonly').keypress(function(e){

			var charCode = (e.which) ? e.which : event.keyCode

			if(String.fromCharCode(charCode).match(/[^0-9+]/g))

			return false;

		});

    });

    let saveDataURL = "{{url('/admin/edit-product/'.$row_id)}}";

	let editid = "{{base64_decode($row_id)}}";

	var returnURL = "{{url('/admin/edit-product/'.$row_id)}}";

</script> 

<script src="{{ asset('public/admin/js/pages/products/add-page.js') }}"></script> 

@endsection