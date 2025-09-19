@extends('layout.admin.dashboard')

@section('content')
<div class="page-heading">
  <div class="page-title">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>View Order #{{$rowData->invoice_id}}</h3>
      </div>
      <div class="col-12 col-md-6 order-md-2 order-first">
        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{url('/admin/orders')}}">Order</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Order</li>
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

                  <div class="row">

                    <div class="col-md-12 col-12"><h6 style="background-color: #f2f7ff;padding: 9px;" for="basicInput">Customer Details</h6></div>
                  	
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Invoice:</b> #{{$rowData->invoice_id}}</label>
                      </div>
                    </div> 
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="basicInput"><b>Txn ID:</b> {{$rowData->payment_id}}</label>
                      </div>
                    </div> 
                     
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Payer ID:</b> {{$rowData->payer_id}}</label>
                      </div>
                    </div> 
                                       
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Name:</b> {{$rowData->customer_name}}</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Email:</b> {{$rowData->customer_email}}</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Mobile:</b> {{$rowData->customer_mobile}}</label>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="basicInput"><b>Address:</b> {!! nl2br($rowData->customer_address) !!}</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>City:</b> {!! nl2br($rowData->customer_city) !!}</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>State:</b> {!! nl2br($rowData->customer_state) !!}</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Country:</b> {!! nl2br($rowData->customer_country) !!}</label>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="basicInput"><b>Zipcode:</b> {!! nl2br($rowData->customer_zipcode) !!}</label>
                      </div>
                    </div>
                    @if($rowData->coupon_code != '')
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="basicInput"><b>Couponcode:</b> {!! nl2br($rowData->coupon_code) !!}</label>
                      </div>
                    </div>
					@endif
                    <div class="col-md-12 col-12"><h6 style="background-color: #f2f7ff;padding: 9px;" for="basicInput">Order Details</h6></div>

                    <table class="table">
                      <tr>
                        <th width="50%">Product</th>                        
                        <th>Order Status</th>
                        <th>Order Date</th>
                        <th>Price</th>
                      </tr>
                      <tr>
                      <td>{!! Helper::getProduct($rowData->product_id,'title') !!}</td>                      
                      <td>{!! $rowData->order_status !!}</td>
                      <td>{!! date('d-m-Y',strtotime($rowData->created_at)) !!}</td>
                      <td>${!! number_format($rowData->total + $rowData->discount,2) !!}</td>
                      </tr>
                      <tr>
                      	<td colspan="2"></td>
                        <td><b>Discount</b></td>
                        <td><b>-${{number_format($rowData->discount,2)}}</b></td>                        
                      </tr>
                      <tr>
                      	<td colspan="2"></td>
                        <td><b>Total</b></td>
                        <td><b>${{number_format($rowData->total,2)}}</b></td>                        
                      </tr>
                    </table>
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
    
@endsection