@extends('layout.admin.dashboard')

@section('content')

<div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Orders</h3>
                    <p class="text-subtitle text-muted">Orders list.</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Orders</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
               <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Compact form-->
                    <form id="searchForm" name="searchForm" class="float-start">
                        <div class="d-flex align-items-center  w-md-800px">
                            <!--begin::Input group-->                            
                            <div class="position-relative w-md-200px me-md-2">
                                <select name="order_status" id="order_status" class="form-select" confirmation="false">
                                	<option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="position-relative w-md-200px me-md-2">
                                <input type="text" name="customer_name" id="customer_name" placeholder="Customer Name" class="form-control"/>
                            </div>
                            <div class="position-relative w-md-200px me-md-2">
                                <input type="date" id="from_date" name="from_date" class="form-control"/>
                            </div>
                            <div class="position-relative w-md-200px me-md-2">
                                <input type="date" id="to_date" name="to_date" class="form-control"/>
                            </div>
                            <!--end::Input group-->
                            <!--begin:Action-->
                            <div class="d-flex align-items-center">
                                <button type="button" id="searchbuttons" onclick="filterData('search');" style="margin-right:10px;" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Search</button>
                                <button type="reset" class="btn btn-sm btn-dark btn-active-light-primary me-5" data-kt-menu-dismiss="true"  onclick="resetFilterForm();">Reset</button>
                            </div>
                            <!--end:Action-->
                        </div>                        
                    </form>
                    <a onclick="exportData();" id="exportCsvBtn" class="btn icon btn-sm btn-outline-primary float-end" style="margin-left:5px">Export CSV</a>
                </div>
                <!--end::Card body-->
            </div>
        </section>
        <!-- Table head options start -->
        <section class="section">
            <div class="row" id="table-head">
                <div class="col-12">
                    <div class="card">

                        <div class="card-content">
                            <!-- table head dark -->
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>CUSTOMER DETAILS</th>
                                            <th>ORDER DETAILS</th>
                                            <th>PAYMENT STATUS</th>
                                            <th>STATUS</th>
                                            <th>CREATED</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody id="replaceHtml">
                                        <tr>
                                            <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Table head options end -->
    </div>
    
<script>
function exportData(){
    $('#exportCsvBtn').html('......');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "POST",
        url: "{{route('exports.orders')}}",
        data: $('#searchForm').serialize(),
        success: function(msg){
            $('#exportCsvBtn').html('Export CSV');
            window.location.href = msg;
        },error: function(ts){
            $('#error500').modal('show');
        }
    });
    return false;
}
function updateOrderStatus(row,value){
    swal({
    title: "Are you sure?",
    text: "",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            data: {row:row,value:value},
            url: "{{ url('/admin/update-order-status') }}",
            success: function(msg){
                if(msg == "Success"){
                    swal({
                    title: 'Success',
                    text: 'Order staus updated successfully.',
                    type: 'success',
                    confirmButtonText: 'Ok',
                    confirmButtonColor: "#009EF7"});
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
        filterData('simple');
       // swal("Order status is pending!");
    }
    });
}
$(document).ready(function(){
    filterData('simple');
});
function filterData(type = null){
    if(type =='search'){$('#searchbuttons').html('Searching..');}
	$.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
		data: $('#searchForm').serialize(),
		url: "{{ url('/admin/orders_paginate') }}",
		success: function(response){
			$('#replaceHtml').html(response);
            $('#searchbuttons').html('Search');
		}
	});
}
</script>

@endsection


