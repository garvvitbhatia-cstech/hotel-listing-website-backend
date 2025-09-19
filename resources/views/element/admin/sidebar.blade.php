@php
	$action =  Route::getCurrentRoute()->getName();
@endphp

<div id="sidebar" class="active">

  <div class="sidebar-wrapper active">

    <div class="sidebar-header">

      <div class="d-flex justify-content-between">

         <div class="logo"> <a href="{{ url('/admin/dashboard'); }}">

            <!--<img src="{{URL::asset('public/img/home/logo1.png')}}" style="height:auto;max-width:100%" alt="Logo" />-->
            Luxi Days

         </a> </div>

         <div class="toggler"> <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a> </div>

       </div>

    </div>

     <div class="sidebar-menu">

      <ul class="menu">

         <li class="sidebar-item {{$action =='admin.dashboard' ?'active':''}}"> 

         	<a href="{{ url('/admin/dashboard'); }}" class='sidebar-link'> <i class="bi bi-grid-fill"></i> <span>{{Session::get('admin_type')}} Dashboard</span> </a> 

         </li>

         @php

         $managerActive =

         $profile =

         $changePassword =

         $accounts =

         $settings =

         false;         

         if($action =='admin.update-profile'){

         	$managerActive = $profile = true;

         }

         if($action =='admin.change-password'){

         	$managerActive = $changePassword = true;

         }

         if($action =='admin.accounts'){

         	$managerActive = $accounts = true;

         }   

         if($action =='admin.settings'){

         	$managerActive = $settings = true;

         }        

         @endphp

         <li class="sidebar-item  has-sub {{$managerActive?'active':''}}"> 

         	<a href="#" class='sidebar-link'> <i class="bi bi-person-fill"></i> <span>My Profile</span> </a>

          <ul class="submenu {{$managerActive?'active':''}}">

             <li class="submenu-item {{$profile?'active':''}}"> 

             	<a href="{{ url('/admin/update-profile'); }}">Update Profile</a> 

             </li>

             <li class="submenu-item {{$changePassword?'active':''}} "> 

             	<a href="{{ url('/admin/change-password'); }}">Change Password</a> 

             </li>

             @if(Session::get('admin_type') == 'Admin')
             <li class="submenu-item {{$settings?'active':''}} "> 

             	<a href="{{ url('/admin/settings'); }}">Settings</a> 

             </li>
             @endif

             

             @if(Session::get('admin_type') == 'Admin')

            <li class="submenu-item {{$accounts?'active':''}}"> 

             	<a href="{{ url('/admin/accounts'); }}">Accounts</a> 

             </li>

             @endif

           </ul>

        </li>

         @if(Session::get('admin_type') == 'Admin')

         @php                        

         $managerActive =

         $users =

         $admins =

         $inner_pages =  

         $banners =  

         $enquiries = 

         false;

        

        if($action =='admin.banners' ||  $action =='admin.add-banner' ||  $action =='admin.edit-banner'){

        $managerActive = $banners = true;

        }

        if($action =='admin.admins' ||  $action =='admin.add-admins' ||  $action =='admin.edit-admins'){

        $managerActive = $admins = true;

        }

         if($action =='admin.users' ||  $action =='admin.add-user' ||  $action =='admin.edit-user'){

         	$managerActive = $users = true;

         }

         if($action =='admin.inner-pages' || $action =='admin.edit-inner-page'){

         	$managerActive = $inner_pages = true;

       	 }

         if($action =='admin.enquiries' || $action =='admin.view-enquiry'){

         	$managerActive = $enquiries = true;

       	 } 

         @endphp

         <li class="sidebar-item  has-sub {{$managerActive?'active':''}}"> 

         	<a href="#" class='sidebar-link'> <i class="bi bi-file-earmark-image-fill"></i> <span>Inner Pages</span> </a>

          <ul class="submenu {{$managerActive?'active':''}}">

          

             <!---<li class="submenu-item {{$users?'active':''}}"> 

             	<a href="{{ url('/admin/users'); }}">Customers </a> 

             </li>--->

             @if(Session::get('admin_type') == 'Admin')

             <!--<li class="submenu-item {{$admins?'active':''}}"> 

             	<a href="{{ url('/admin/admins'); }}">Admin Manager</a> 

             </li>--->
             

             @endif

            <li class="submenu-item {{$inner_pages?'active':''}}">

                <a href="{{ url('/admin/inner-pages'); }}">Inner Pages</a>

            </li>

            <li class="submenu-item {{$enquiries?'active':''}}">

                <a href="{{ url('/admin/enquiries'); }}">Enquiries</a>

            </li>

            <li class="submenu-item {{$banners?'active':''}}"> 

             	<a href="{{ url('/admin/banners'); }}">Banners</a> 

             </li>

           </ul>

        </li>

         @endif

         @if(Session::get('admin_type') == 'Admin')

         @php                        

         $managerActive =

         $testimonials =  

         $services =

         $teams = 

         $clients =  

         $coupon_code = 

         $blogs =  

         false;

         if($action =='admin.services' ||  $action =='admin.add-service' ||  $action =='admin.edit-service'){

         	$managerActive = $services = true;

         }

         if($action =='admin.blogs' || $action =='admin.add-blog' || $action =='admin.edit-blog'){

            $managerActive = $blogs = true;

        } 

        if($action =='admin.our-clients' || $action =='admin.add-our-client' || $action =='admin.edit-our-client'){

            $managerActive = $clients = true;

        } 

         if($action =='admin.testimonials' || $action =='admin.add-testimonial' || $action =='admin.edit-testimonial'){

         	$managerActive = $testimonials = true;

       	 } 

         if($action =='admin.coupon-codes' || $action =='admin.add-coupon-code' || $action =='admin.edit-coupon-code'){

         	$managerActive = $coupon_code = true;

       	 }

         if($action =='admin.teams' || $action =='admin.add-team' || $action =='admin.edit-teams'){

         	$managerActive = $teams = true;

       	 }

         @endphp

         <li class="sidebar-item  has-sub {{$managerActive?'active':''}}"> 

         	<a href="#" class='sidebar-link'> <i class="bi bi-file-check-fill"></i> <span>Blogs</span> </a>

          <ul class="submenu {{$managerActive?'active':''}}">          

            <li class="submenu-item {{$blogs?'active':''}}">

                <a href="{{ url('/admin/blogs'); }}">Blog/News</a>

            </li>

            <li class="submenu-item {{$services?'active':''}}"> 

             	<a href="{{ url('/admin/services'); }}">Stories</a> 

            </li> 

            <li class="submenu-item {{$testimonials?'active':''}}">

                <a href="{{ url('/admin/testimonials'); }}">Testimonials</a>

            </li>
            
            <li class="submenu-item {{$clients?'active':''}}">

                <a href="{{ url('/admin/our-clients'); }}">Our Clients</a>

            </li>
                        
            <!---<li class="submenu-item {{$teams?'active':''}}">

                <a href="{{ url('/admin/teams'); }}">Team</a>

            </li>

            <li class="submenu-item {{$coupon_code?'active':''}}">

                <a href="{{ url('/admin/coupon-codes'); }}">Coupon Code</a>

            </li>--->

           </ul>

        </li>

         @endif

         @if(Session::get('admin_type') == 'Admin')

         @php                        

         $managerActive =

         $categories =

         $sub_categories =

         $products =

         $orders =

         false;

         if($action =='admin.products' ||  $action =='admin.add-product' ||  $action =='admin.edit-product'){

         	$managerActive = $products = true;

         }

         if($action =='admin.categories' ||  $action =='admin.add-category' ||  $action =='admin.edit-category'){

        $managerActive = $categories = true;

        }

        if($action =='admin.sub-categories' ||  $action =='admin.add-sub-category' ||  $action =='admin.edit-sub-category'){

        $managerActive = $sub_categories = true;

        }

         if($action =='admin.orders' || $action =='admin.view-order'){

         	$managerActive = $orders = true;

       	 }

         @endphp

         <li class="sidebar-item  has-sub {{$managerActive?'active':''}}"> 

         	<a href="#" class='sidebar-link'> <i class="bi bi-bag-fill"></i> <span>Hotel/Property</span> </a>

          <ul class="submenu {{$managerActive?'active':''}}">          

            <li class="submenu-item {{$categories?'active':''}}">

                <a href="{{ url('/admin/categories'); }}">Categories</a>

            </li>

            <li class="submenu-item {{$sub_categories?'active':''}}">

                <a href="{{ url('/admin/sub-categories'); }}">Sub Categories</a>

            </li>            

            <li class="submenu-item {{$products?'active':''}}">

                <a href="{{ url('/admin/products'); }}">Hotels</a>

            </li>

            <!---<li class="submenu-item {{$orders?'active':''}}"> 

             	<a href="{{ url('/admin/orders'); }}">Orders</a> 

            </li>--->

           </ul>

        </li>

         @endif
         
         

         @if(Session::get('admin_type') == 'Admin' || Session::get('admin_type') == 'Account')

         @php                        

         $managerActive =

         $memberships  =

         $experiences =
         
         $requets =
         
         $records =

         false;

         if($action =='admin.memberships' ||  $action =='admin.add-membership' ||  $action =='admin.edit-membership'){

        $managerActive = $memberships = true;

        }

        if($action =='admin.experience' ||  $action =='admin.add-experience' ||  $action =='admin.edit-experience'){

        $managerActive = $experiences = true;

        }
        
        if($action =='admin.membership.request' ||  $action =='admin.add-membership-request' ||  $action =='admin.edit-membership-request'){

        $managerActive = $requets = true;

        }
        
        if($action =='admin.membership_records'){

        	$managerActive = $records = true;

        }

         @endphp

         <li class="sidebar-item  has-sub {{$managerActive?'active':''}}"> 

         	<a href="#" class='sidebar-link'> <i class="bi bi-bag-fill"></i> <span>Membership</span> </a>

          <ul class="submenu {{$managerActive?'active':''}}"> 
          
          @if(Session::get('admin_type') == 'Admin')         

            <li class="submenu-item {{$memberships?'active':''}}">

                <a href="{{ url('/admin/memberships'); }}">Memberships</a>

            </li>

            <li class="submenu-item {{$experiences?'active':''}}">

                <a href="{{ url('/admin/experience'); }}">Experience</a>

            </li>
            
            @endif
            
            <li class="submenu-item {{$requets?'active':''}}">

                <a href="{{ url('/admin/membership-request'); }}">Requests</a>

            </li>
            
            <li class="submenu-item {{$records?'active':''}}">

                <a href="{{ url('/admin/membership-records'); }}">Records</a>

            </li>

           </ul>

        </li>

         @endif

         
        
         @if(Session::get('admin_type') == 'Admin' || Session::get('admin_type') == 'Staff')

         @php                        

         $managerActive =

         $employee_attandance  =

         $attandance  =

         $staff =

         false;

         if($action =='admin.employee-attendence'){

        $managerActive = $employee_attandance = true;

        }

        if($action =='admin.attendence'){

        $managerActive = $attandance = true;

        }

        if($action =='admin.staff' ||  $action =='admin.add-staff' ||  $action =='admin.edit-staff'){

            $managerActive = $staff = true;

        }

         @endphp

         <li class="sidebar-item  has-sub {{$managerActive?'active':''}}"> 

         	<a href="#" class='sidebar-link'> <i class="bi bi-bag-fill"></i> <span>Employee</span> </a>

          <ul class="submenu {{$managerActive?'active':''}}">          

            @if(Session::get('admin_type') == 'Admin')
            <li class="submenu-item {{$staff?'active':''}}"> 

                <a href="{{ url('/admin/staff'); }}">Staff </a>

            </li>
            @endif
            <li class="submenu-item {{$attandance?'active':''}}">

            <a href="{{ url('/admin/attendence'); }}">Employee Attandance</a>

            </li>


           </ul>

        </li>

         @endif
        
         @if(Session::get('admin_type') == 'Admin')
         <li class="sidebar-item"> <a href="{{ url('/admin/packages'); }}" class='sidebar-link'> <i class="bi bi-box-seam"></i> <span>Packages</span> </a> </li>
         @endif

         <li class="sidebar-item"> <a href="{{ url('/admin/logout'); }}" class='sidebar-link'> <i class="bi bi-box-arrow-right"></i> <span>Log Out</span> </a> </li>

       </ul>

    </div>

     <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>

   </div>

</div>