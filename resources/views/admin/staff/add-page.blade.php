@extends('layout.admin.dashboard')

@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>Add New Employee</h3>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('/admin/staff')}}">Employee</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
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
                <li class="nav-item" role="presentation"> <a class="nav-link" id="description-tab" data-bs-toggle="tab" href="#address"
                                                    role="tab" aria-controls="address" aria-selected="false">Address Info</a> </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="seo-tab" data-bs-toggle="tab" href="#emergency"
                                                    role="tab" aria-controls="emergency" aria-selected="false">Emergency Contact Info</a> </li>
                <!---<li class="nav-item" role="presentation"> <a class="nav-link" id="seo-tab" data-bs-toggle="tab" href="#billing-address"
                                                    role="tab" aria-controls="billing-address" aria-selected="false">Billing Address Info</a> </li>--->
              </ul>
              <hr />
              <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Name</label>
                        <input type="text" class="form-control" placeholder="Enter Name" value="" name="name" id="name">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Email Address</label>
                        <input type="text" class="form-control" placeholder="Enter Email" value="" name="email" id="email">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Mobile</label>
                        <input type="text" class="numberonly form-control" maxlength="10" placeholder="Enter Mobile" value="" name="mobile" id="mobile">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Gender</label>
                        <select class="form-select" value="" name="gender" id="gender">
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Other">Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Password</label>
                        <input type="password" class="form-control" value="" name="password" id="password">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Confirm Password</label>
                        <input type="password" class="form-control" value="" name="confirm_password" id="confirm_password">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Profile Image</label>
                        <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade " id="emergency" role="tabpanel" aria-labelledby="emergency-tab">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Emergency Contact Name</label>
                        <input type="text" class="form-control numberonly" maxlength="10" placeholder="Enter Emergency Contact Name" name="emergency_contact_name" id="emergency_contact_name">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="basicInput">Emergency Contact Mobile</label>
                        <input type="text" class="form-control numberonly" maxlength="10" placeholder="Enter Emergency Contact Mobile" name="emergency_contact_number" id="emergency_contact_number">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade " id="address" role="tabpanel" aria-labelledby="address-tab">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="basicInput">Address</label>
                        <input type="text" class="form-control" placeholder="Enter Address" name="address" id="address">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="basicInput">City</label>
                        <input type="text" class="form-control" placeholder="Enter City" name="city" id="city">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="basicInput">Zipcode</label>
                        <input type="text" class="form-control numberonly" maxlength="6" placeholder="Enter Zipcode" name="zipcode" id="zipcode">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade " id="billing-address" role="tabpanel" aria-labelledby="billing-address-tab">
                  <div class="row">
                    <div class="card-header">
                      <h4 class="card-title">Billing Address</h4>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">First Name</label>
                            <input type="text" class="form-control" placeholder="Enter First Name" name="billing_first_name" id="billing_first_name">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">Last Name</label>
                            <input type="text" class="form-control" placeholder="Enter Last Name" name="billing_last_name" id="billing_last_name">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">Phone Number</label>
                            <input type="text" class="form-control numberonly" placeholder="Enter Phone Number" name="billing_phone" id="billing_phone">
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="form-group">
                            <label for="basicInput">Address</label>
                            <input type="text" class="form-control" placeholder="Enter Address" name="billing_address" id="billing_address">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">City</label>
                            <input type="text" class="form-control" placeholder="Enter City" name="billing_city" id="billing_city">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">Zip/postal code</label>
                            <input type="text" class="form-control" placeholder="Enter Zipcode" name="billing_zipcode" id="billing_zipcode">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">State/Province</label>
                            <input type="text" class="form-control" placeholder="Enter State/Province" name="billing_state" id="billing_state">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="basicInput">Country</label>
                            <input type="text" class="form-control" placeholder="Enter Country" name="billing_country" id="billing_country">
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="basicInput">Are billing and shipping addresses the same ?</label>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sameAddress" id="sameAddress" checked value="Yes" >
                              <label class="form-check-label" for="sameAddress">Yes</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="sameAddress"  id="sameAddress1" checked value="No" >
                              <label class="form-check-label" for="sameAddress1"> No </label>
                            </div>
                          </div>
                        </div>
                        <div class="row p-0" id="ShippingAddress">
                          <div class="card-header">
                            <h4 class="card-title">Shipping Address</h4>
                          </div>
                          <div class="card-body">
                            <div class="row">
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="basicInput">First Name</label>
                                  <input type="text" class="form-control" placeholder="Enter First Name" name="shipping_first_name" id="shipping_first_name">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="basicInput">Last Name</label>
                                  <input type="text" class="form-control" placeholder="Enter Last Name" name="shipping_last_name" id="shipping_last_name">
                                </div>
                              </div>
                              <div class="col-md-4"> 
                                <div class="form-group">
                                  <label for="basicInput">Phone Number</label>
                                  <input type="text" class="form-control numberonly" maxlength="10" placeholder="Enter Phone Number" name="shipping_phone" id="shipping_phone">
                                </div>
                              </div>
                              <div class="col-md-8">
                                <div class="form-group">
                                  <label for="basicInput">Address</label>
                                  <input type="text" class="form-control" placeholder="Enter Address" name="shipping_address" id="shipping_address">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="basicInput">City</label>
                                  <input type="text" class="form-control" placeholder="Enter City" name="shippingg_city" id="shipping_city">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="basicInput">Zip/postal code</label>
                                  <input type="text" class="form-control numberonly" placeholder="Enter Zipcode" name="shipping_zipcode" id="shipping_zipcode">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="basicInput">State/Province</label>
                                  <input type="text" class="form-control" placeholder="Enter State/Province" name="shipping_state" id="shipping_state">
                                </div>
                              </div>
                              <div class="col-md-4">
                                <div class="form-group">
                                  <label for="basicInput">Country</label>
                                  <input type="text" class="form-control" placeholder="Enter Country" name="shipping_country" id="shipping_country">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
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
    $(document).ready(function () {
      $('.numberonly').keypress(function(e){
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9+]/g))
          return false;
      });	
    });
    let saveDataURL = "{{url('/admin/add-staff')}}";
    let returnURL = "{{url('/admin/staff')}}";
</script> 
<script src="{{ asset('public/admin/js/pages/users/add-page.js') }}"></script> 
@endsection 