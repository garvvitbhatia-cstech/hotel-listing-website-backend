@extends('layout.admin.dashboard')



@section('content')

    <div class="page-heading">

        <h3>Employee Attendance Dashboard</h3>

    </div>

    <div class="page-content">

        <section class="row">    
            
        <div class="col-12 col-lg-4">

                <div class="card">

                    <div class="card-body py-4 px-5">

                        <div class="align-items-center">

                            <div class="ms-3 name text-center">

                                <h3 class="font-bold">Mark Your Attendance</h3>

                                <h6 class="text-muted mb-0"><a class="btn btn-primary" href="{{url('admin/employee-attendence')}}">Click Now</a></h6>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <div class="col-12 col-lg-4">

            <div class="card">

                <div class="card-body py-4 px-5">

                    <div class="d-flex align-items-center">

                        <div class="avatar avatar-xl">

                            <img src="{{ asset('public/admin/images/faces/1.jpg') }}" alt="Face 1">

                        </div>

                        <div class="ms-3 name">

                            <h5 class="font-bold">{{Session::get('admin_name')}}</h5>

                            <h6 class="text-muted mb-0">{{Session::get('admin_email')}}</h6>

                        </div>

                    </div>

                </div>

            </div>

            </div>
            
            

        </section>

    </div>

    <?php /*?><script src="{{ asset('public/admin/vendors/apexcharts/apexcharts.js') }}"></script>

    <script src="{{ asset('public/admin/js/pages/dashboard.js') }}"></script><?php */?>

@endsection

