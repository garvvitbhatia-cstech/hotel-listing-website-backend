@if($records->count()>0)
    @foreach($records as $key => $row)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                {!! $row->id !!}
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                {!! $row->name !!}
            </div>
        </td>
        <td>
            <div class="d-flex align-items-center">
                {!! $row->flat_no !!} {!! $row->building !!} {!! $row->street !!} {!! $row->area !!} {!! $row->landmark !!} {!! $row->city !!} {!! $row->state !!} {!! $row->country !!} {!! $row->postal_code !!}
            </div>
        </td>
         <td>
            <div class="d-flex align-items-center">
                {!! $row->mobile !!}
            </div>
        </td>
         <td>
            <div class="d-flex align-items-center">
                {!! $row->email !!}
            </div>
        </td>
       
        
        <td>
            <span class="text-muted fw-bold text-muted d-block fs-7">{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</span>
        </td>
        <td>
            <!--<a href="{{ url('/admin/edit-membership-request',base64_encode($row->id)) }}" class="btn btn-sm btn-primary" title="Edit">
                <i class="bi bi-pencil"></i>
            </a>-->
            <a href="javascript:void(0);" onclick="deleteData('membership_records','{{ $row->id }}');" class="btn btn-sm btn-danger"  title="Delete">
                <i class="bi bi-trash"></i>
            </a>
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td align="center" colspan="12">Record not found</td>
    </tr>
    @endif
    <tr>
        <td align="center" colspan="10">
            <div id="pagination">{{{ $records->links() }}}</div>
        </td>
    </tr>