@extends('layout.admin.dashboard')

@section('content')

<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Edit Coupon Code</h3>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{url('/admin/coupon-codes')}}">Coupon Codes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Coupon Code</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Coupon Code</h4>
                </div>
                <div class="card-body">
                <form class="form w-100" id="pageForm" action="#">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="basicInput">Coupon Code</label>
                                <input type="text" class="form-control" value="{{$rowData->title}}" maxlength="8" style="text-transform:uppercase" name="title" id="title">
                            </div>
                        </div>                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="basicInput">Discount Type</label>
                                <select class="form-control" name="discount_type" id="discount_type">
                                <option value="">Select Discount Type</option>
                                <option @if($rowData->discount_type == 'Amount') selected @endif value="Amount">Amount</option>
                                <option @if($rowData->discount_type == 'Percent') selected @endif value="Percent">Percent</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="basicInput">Amount</label>
                                <input type="text" value="{{$rowData->amount}}" class="form-control numberonly" maxlength="4" name="amount" id="amount">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="basicInput">Start Date</label>
                                <input type="date" class="form-control" value="{{$rowData->start_date}}" name="start_date" id="start_date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="basicInput">End Date</label>
                                <input type="date" class="form-control" value="{{$rowData->end_date}}" name="end_date" id="end_date">
                            </div>
                        </div>
                         
                        <div class="text-left">
                            <!--begin::Submit button-->
                            <button type="button" id="form_submit" class="btn btn-sm btn-primary fw-bolder me-3 my-2">
                                <span class="indicator-label" id="formSubmit">Submit</span>
                                <span class="indicator-progress d-none">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                            <!--end::Submit button-->
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
<!-- end plugin js -->
<script>
    $('.numberonly').keypress(function(e){
			var charCode = (e.which) ? e.which : event.keyCode
			if(String.fromCharCode(charCode).match(/[^0-9+]/g))
			return false;
		});
    let saveDataURL = "{{url('/admin/edit-coupon-code/'.$row_id)}}";
    let returnURL = "{{url('/admin/coupon-codes')}}";
</script>
<script src="{{ asset('public/admin/js/pages/coupon_codes/add-page.js') }}"></script>

@endsection