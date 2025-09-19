@extends('layout.admin.dashboard')

@section('content')

<div class="page-heading">

  <div class="page-title">

    <div class="row">

      <div class="col-12 col-md-6 order-md-1 order-last">

        <h3>Add Hotel</h3>

      </div>

      <div class="col-12 col-md-6 order-md-2 order-first">

        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">

          <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>

            <li class="breadcrumb-item"><a href="{{url('/admin/products')}}">Hotels</a></li>

            <li class="breadcrumb-item active" aria-current="page">Add Hotel</li>

          </ol>

        </nav>

      </div>

    </div>

  </div>

  <section class="section">

    <form class="form w-100" id="pageForm" action="#">

      <div class="row">

        <div class="col-9 col-md-9">

          <div class="card">

            <div class="card-body">

              <ul class="nav nav-tabs" id="myTab" role="tablist">

                <li class="nav-item" role="presentation"> <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"

                                                    role="tab" aria-controls="home" aria-selected="true">General Info</a> </li>

                <li class="nav-item" role="presentation"> <a class="nav-link" id="seo-tab" data-bs-toggle="tab" href="#seo"

                                                    role="tab" aria-controls="seo" aria-selected="false">SEO Info</a> </li>

              </ul>

              <hr />

              <div class="tab-content mt-5" id="myTabContent">

                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                  <div class="row">
                  
                  <div class="col-md-2">

                      <div class="form-group">

                        <label for="basicInput">B2B</label>

                        <select class="form-select" name="is_b2b" id="is_b2b">

                          <option value="1">Yes</option>
                          <option value="2">No</option>

                        </select>

                      </div>

                    </div>
                  
                  <div class="col-md-2">

                      <div class="form-group">

                        <label for="basicInput">Type</label>

                        <select class="form-select" name="type" id="type">

                          <option value="Domestic">Domestic</option>
                          <option value="International">International</option>

                        </select>

                      </div>

                    </div>             	

                  <div class="col-md-4">

                    <div class="form-group">

                      <label for="basicInput">Category</label>

                      <select class="form-select" name="category_id" id="category_id" onchange="getSubCategory(this.value)">

                        <option value="">Select Category</option>

                          @foreach($category_list as $key => $category)

                            <option value="{{$key}}">{{$category}}</option>

                          @endforeach

                      </select>

                    </div>

                    </div>

                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Sub Category</label>

                        <select class="form-select" name="sub_category_id" id="sub_category_id">

                          <option value="">Select Sub Category</option>

                        </select>

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">Title</label>

                        <input type="text" class="form-control" placeholder="Enter Title" value="" name="title" id="title">

                      </div>

                    </div>
                    
                    <div class="col-md-3">

                      <div class="form-group">

                        <label for="basicInput">Location</label>

                        <select class="form-select" name="location" id="location">
                            <option value="">Select</option>
                            <option value="North">North</option>
                            <option value="West">West</option>
                            <option value="South">South</option>
                            <option value="East">East</option>

                        </select>

                      </div>

                    </div>
                    
                    <div class="col-md-3">

                      <div class="form-group">

                        <label for="basicInput">Terrain</label>

                        <select class="form-select" name="terrain" id="terrain">

                            <option value="">Search</option>
                            <option value="Beach">Beach</option>
                            <option value="City">City</option>
                            <option value="Desert">Desert</option>
                            <option value="Hill station">Hill station</option>
                            <option value="Himalayan">Himalayan</option>
                            <option value="Jungle">Jungle</option>
                            <option value="Waterfront">Waterfront</option>

                        </select>

                      </div>

                    </div>
                    
                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Address</label>

                        <input type="text" class="form-control" value="" name="address" id="address">

                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">City</label>

                        <input type="text" class="form-control" name="city" id="city">

                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">State</label>

                        <input type="text" class="form-control" name="state" id="state">

                      </div>

                    </div>
                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Country</label>

                        <input type="text" class="form-control" name="country" value="India" id="country">

                      </div>

                    </div>

                    <?php /* ?><div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">Price</label>

                        <input type="text" class="form-control numberonly" maxlength="5" placeholder="Enter price" value="" name="price" id="price">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">Quantity</label>

                        <input type="text" class="form-control numberonly" placeholder="Enter Quantity" maxlength="4" value="" name="quantity" id="quantity">

                      </div>

                    </div><?php */ ?>

                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">Description</label>

                        <textarea class="form-control editorBox" placeholder="Enter Description" name="description" id="description"></textarea>

                      </div>

                    </div>                    

                    <div class="col-md-4">

                      <div class="form-group">

                        <label for="basicInput">Banner</label>

                        <input type="file" class="form-control" name="image" id="image" accept="image/*">

                      </div>

                    </div>                 

                    </div>                     

                </div>

                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">

                  <div class="row">

                    <div class="col-md-12">

                      <div class="form-group">

                        <label for="basicInput">SEO Title</label>

                        <input type="text" class="form-control" placeholder="Enter SEO Title" value="" name="seo_title" id="seo_title">

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">SEO Description</label>

                        <textarea class="form-control" rows="6" placeholder="Enter SEO Description" name="seo_description" id="seo_description"></textarea>

                      </div>

                    </div>

                    <div class="col-md-6">

                      <div class="form-group">

                        <label for="basicInput">SEO Keywords</label>

                        <textarea class="form-control" rows="6" placeholder="Enter SEO Keywords" name="seo_keyword" id="seo_keyword"></textarea>

                      </div>

                    </div>

                    <div class="col-md-6">

                    	<div class="form-group">

                        	<label for="basicInput">SEO Robots</label>

                        	<select id="robot_tags" name="robot_tags" value="index,nofollow" class="form-select">

                                <option value="index,follow">index,follow</option>

                                <option value="index,nofollow">index,nofollow</option>

                                <option value="noindex,follow">noindex,follow</option>

                                <option value="noindex,nofollow">noindex,nofollow</option>

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

    let saveDataURL = "{{url('/admin/add-product/')}}";

    let returnURL = "{{url('/admin/products')}}";

</script> 

<script src="{{ asset('public/admin/js/pages/products/add-page.js') }}"></script> 

@endsection