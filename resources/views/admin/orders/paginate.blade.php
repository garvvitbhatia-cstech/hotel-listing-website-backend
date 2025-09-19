@if($records->count()>0)
    @foreach($records as $key => $row)
    @php
    	$class = '';    	
    @endphp
    <tr>
        <td >
            
                <b>Name:</b> {!! $row->customer_name !!}<br>
                <b>Email:</b> {!! $row->customer_email !!}<br>
                <b> Mobile:</b> {!! $row->customer_mobile !!}
        
        </td>
        <td>
            
            	<b>Invoice ID:</b> #{!! $row->invoice_id !!}<br>
                <b>Amount:</b> ${!! number_format($row->total,2 ) !!}<br>
                @if($row->payment_id != '')<b>Txn ID:</b> {!! $row->payment_id !!} @endif
           
        </td>
        <td>{!! $row->payment_status !!}</td>
        <td>
            <select class="form-select" name="order_status" id="order_status" onchange="updateOrderStatus('{{$row->id}}',this.value)">
                <option {{$row->order_status == 'Pending'?'selected':''}}  value="Pending">Pending</option>
                <option {{$row->order_status == 'Processing'?'selected':''}} value="Processing">Processing</option>
                <option {{$row->order_status == 'Delivered'?'selected':''}} value="Delivered">Delivered</option>
                <option {{$row->order_status == 'Cancelled'?'selected':''}} value="Cancelled">Cancelled</option>
            </select>
        </td>
        <td>
            <span class="{{$class}} align-items-center">{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</span>
        </td>
        <td>
            <a href="{{ url('/admin/view-order',base64_encode($row->id)) }}" class="btn btn-sm btn-primary" title="View">
                <i class="bi bi-eye"></i>
            </a>
            <!--<a href="javascript:void(0);" onclick="deleteData('report_enquiries','{{ $row->id }}');" class="btn btn-sm btn-danger"  title="Delete">
                <i class="bi bi-trash"></i>
            </a>-->
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td align="center" colspan="10">Record not found</td>
    </tr>
    @endif
    <tr>
        <td align="center" colspan="10">
            <div id="pagination">{{{ $records->links() }}}</div>
        </td>
    </tr>