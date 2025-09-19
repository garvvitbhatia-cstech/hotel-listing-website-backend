@extends('layout.admin.dashboard')

@section('content')

<div class="page-heading">


  <div class="page-title">


    <div class="row">


      <div class="col-12 col-md-6 order-md-1 order-last">


        <h3>Add Blog/News</h3>


      </div>


      <div class="col-12 col-md-6 order-md-2 order-first">


        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">


          <ol class="breadcrumb">


            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>


            <li class="breadcrumb-item"><a href="{{url('/admin/blogs')}}">Blog/News</a></li>


            <li class="breadcrumb-item active" aria-current="page">Add Blog/News</li>


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
                  
                  <div class="col-md-12">


                      <div class="form-group">


                        <label for="basicInput">Type</label>


                        <select class="form-select" id="type" name="type">
                        <option value="Blog">Blog</option>
                        <option value="News">News</option>
                        </select>


                      </div>


                    </div>


                    <div class="col-md-12">


                      <div class="form-group">


                        <label for="basicInput">Title</label>


                        <input type="text" class="form-control" placeholder="Enter Title" value="" name="title" id="title">


                      </div>


                    </div>


                    <div class="col-md-12">


                      <div class="form-group">


                        <label for="basicInput">Description</label>


                        <textarea class="form-control editorBox" placeholder="Enter Description" value="" name="description" id="description"></textarea>


                      </div>


                    </div>


                    <div class="col-md-4">


                      <div class="form-group">


                        <label for="basicInput">Banner (1910 x 350)</label>


                        <input type="file" class="form-control" name="banner" id="banner" accept="image/*">


                      </div>


                    </div>


                    <div class="col-md-4">


                      <div class="form-group">


                        <label for="basicInput">Image</label>


                        <input type="file" class="form-control" name="image" id="image" accept="image/*">


                      </div>


                    </div>                    


                  </div>


                </div>


                <div class="tab-pane fade " id="seo" role="tabpanel" aria-labelledby="seo-tab">


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


<script>


    let saveDataURL = "{{url('/admin/add-blog')}}";


    let returnURL = "{{url('/admin/blogs')}}";


</script> 


<script src="{{ asset('public/admin/js/pages/blogs/add-page.js') }}"></script> 


@endsection 