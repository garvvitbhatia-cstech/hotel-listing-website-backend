@extends('layout.admin.dashboard')
@section('content')

<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Add Membership Request</h3>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('/admin/membership-request')}}">Membership Requests</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Membership Request</li>
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
            <li class="nav-item" role="presentation"> <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">General Info</a> </li>
          </ul>
          <hr />
          <div class="tab-content mt-5" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="basicInput">Hotel Name</label>
                    <input type="text" class="form-control" placeholder="Enter Title" value="" name="hotel_name" id="hotel_name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="basicInput">User Name</label>
                    <input type="text" class="form-control" placeholder="Enter Title" value="" name="username" id="username">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="basicInput">User Phone</label>
                    <input type="text" class="form-control" placeholder="Enter Title" value="" name="phone" id="phone">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="basicInput">Validity</label>
                    <input type="date" class="form-control" placeholder="Enter Title" value="" name="validity" id="validity">
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
      </div>
      </div>
    </form>
  </section>
</div>

<!-- end plugin js --> 

<script>
    let saveDataURL = "{{url('/admin/add-membership-request')}}";
    let returnURL = "{{url('/admin/membership-request')}}";
</script> 
<script src="{{ asset('public/admin/js/pages/memberships_request/add-page.js') }}"></script> 
@endsection 