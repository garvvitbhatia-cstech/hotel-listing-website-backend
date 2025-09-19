@if($records->count()>0)



    @foreach($records as $key => $row)



    <tr>



        <td>



            <div class="d-flex align-items-center">



                {!! $row->user_name !!}



            </div>



        </td>



        <td>



        	@if(!empty($row->user_profile))



            <div class="d-flex align-items-center">



                <div class="cropped" id="cropped"><img src="{{URL::asset('public/admin/images/teams/')}}/{!! $row->user_profile !!}" width="100"></div>



            </div>



            @endif



        </td>



        <td>



            @if($row->status == 1)



            <a href="javascript:void(0);" onclick="changeStatus('teams','{!!$row->id!!}','{!!$row->status!!}');" class="badge bg-success ">Active</a>



            @else



            <a href="javascript:void(0);"  onclick="changeStatus('teams','{!!$row->id!!}','{!!$row->status!!}');" class="badge bg-danger">In-Active</a>



            @endif



        </td>



        <td>



            <span class="text-muted fw-bold text-muted d-block fs-7">{!! date('d M, Y h:i A',strtotime($row->created_at)) !!}</span>



        </td>



        <td>



            <a href="{{ url('/admin/edit-team',base64_encode($row->id)) }}" class="btn btn-sm btn-primary" title="Edit">



                <i class="bi bi-pencil"></i>



            </a>



            <a href="javascript:void(0);" onclick="deleteData('teams','{{ $row->id }}');" class="btn btn-sm btn-danger"  title="Delete">



                <i class="bi bi-trash"></i>



            </a>



        </td>



    </tr>







    @endforeach



    @else



    <tr>



        <td align="center" colspan="6">Record not found</td>



    </tr>



    @endif



    <tr>



        <td align="center" colspan="10">



            <div id="pagination">{{{ $records->links() }}}</div>



        </td>



    </tr>











