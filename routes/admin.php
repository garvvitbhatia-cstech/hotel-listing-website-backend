<?php
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AilmentsController;
use App\Http\Controllers\Admin\AccountsController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\TestimonialsController;
use App\Http\Controllers\Admin\InnerPagesController;
use App\Http\Controllers\Admin\EnquiriesController;
use App\Http\Controllers\Admin\ServicesController;
use App\Http\Controllers\Admin\TeamsController;
use App\Http\Controllers\Admin\CouponCodesController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\SubCategoriesController;
use App\Http\Controllers\Admin\OurClientsController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\PackagesController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\EmployeeAttendenceController;
use App\Http\Controllers\Admin\AttendenceController;
use App\Http\Controllers\Admin\MembershipRequestController;
use App\Http\Controllers\Admin\MembershipRecordController;
/*
|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These


| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/
Route::prefix('admin')->group(function(){
	

    #account setup
    Route::get('/',[AdminController::class, 'login'])->name('admin.login');
    Route::get('/login',[AdminController::class, 'login'])->name('admin.login');



    #dashboard setup
    Route::get('/dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin-login',[AdminController::class, 'admin_login'])->name('admin.admin_login');
    Route::get('/logout',[AdminController::class, 'logout'])->name('admin.logout');	

	

	#accounts
    Route::get('/accounts',[AccountsController::class, 'getList'])->name('admin.accounts');
    Route::any('/accounts_paginate',[AccountsController::class, 'listPaginate'])->name('admin.accounts_paginate');
	Route::any('/edit-account/{row_id}',[AccountsController::class, 'editPage'])->name('admin.edit-account');
	Route::any('/add-account',[AccountsController::class, 'addPage'])->name('admin.add-accounts');

	#super admins
    Route::get('/admins',[AdminsController::class, 'getList'])->name('admin.admins');
    Route::any('/admins_paginate',[AdminsController::class, 'listPaginate'])->name('admin.admins_paginate');
	Route::any('/edit-admin/{row_id}',[AdminsController::class, 'editPage'])->name('admin.edit-admins');
	Route::any('/add-admin',[AdminsController::class, 'addPage'])->name('admin.add-admins');

	#blogs
    Route::get('/blogs',[BlogsController::class, 'getList'])->name('admin.blogs');
    Route::any('/blog_paginate',[BlogsController::class, 'listPaginate'])->name('admin.blog_paginate');
    Route::any('/add-blog',[BlogsController::class, 'addPage'])->name('admin.add-blog');
    Route::any('/edit-blog/{row_id}',[BlogsController::class, 'editPage'])->name('admin.edit-blog');


    #banners
    Route::get('/banners',[BannersController::class, 'getList'])->name('admin.banners');
    Route::any('/banners_paginate',[BannersController::class, 'listPaginate'])->name('admin.banners_paginate');
    Route::any('/add-banner',[BannersController::class, 'addPage'])->name('admin.add-banner');
    Route::any('/edit-banner/{row_id}',[BannersController::class, 'editPage'])->name('admin.edit-banner');

	#testimonials
    Route::get('/testimonials',[TestimonialsController::class, 'getList'])->name('admin.testimonials');
    Route::any('/testimonials_paginate',[TestimonialsController::class, 'listPaginate'])->name('admin.testimonials_paginate');
	Route::any('/edit-testimonial/{row_id}',[TestimonialsController::class, 'editPage'])->name('admin.edit-testimonial');
	Route::any('/add-testimonial',[TestimonialsController::class, 'addPage'])->name('admin.add-testimonial');
	
	#membership-records
    Route::get('/membership-records',[MembershipRecordController::class, 'getList'])->name('admin.membership_records');
    Route::any('/membership_record_paginate',[MembershipRecordController::class, 'listPaginate'])->name('admin.membership_record_paginate');

	#OurClientsController
    Route::get('/our-clients',[OurClientsController::class, 'getList'])->name('admin.our-clients');
    Route::any('/our_clients_paginate',[OurClientsController::class, 'listPaginate'])->name('admin.our_clients_paginate');
	Route::any('/edit-our-client/{row_id}',[OurClientsController::class, 'editPage'])->name('admin.edit-our-client');
	Route::any('/add-our-client',[OurClientsController::class, 'addPage'])->name('admin.add-our-client');

    #testimonials
    Route::get('/teams',[TeamsController::class, 'getList'])->name('admin.teams');
    Route::any('/teams_paginate',[TeamsController::class, 'listPaginate'])->name('admin.teams_paginate');
	Route::any('/edit-team/{row_id}',[TeamsController::class, 'editPage'])->name('admin.edit-team');
	Route::any('/add-team',[TeamsController::class, 'addPage'])->name('admin.add-team');

	#testimonials
    Route::get('/categories',[CategoriesController::class, 'getList'])->name('admin.categories');
    Route::any('/categories_paginate',[CategoriesController::class, 'listPaginate'])->name('admin.categories_paginate');
	Route::any('/edit-category/{row_id}',[CategoriesController::class, 'editPage'])->name('admin.edit-category');
	Route::any('/add-category',[CategoriesController::class, 'addPage'])->name('admin.add-category');

    #testimonials
    Route::get('/sub-categories',[SubCategoriesController::class, 'getList'])->name('admin.sub-categories');
    Route::any('/sub_categories_paginate',[SubCategoriesController::class, 'listPaginate'])->name('admin.sub_categories_paginate');
	Route::any('/edit-sub-category/{row_id}',[SubCategoriesController::class, 'editPage'])->name('admin.edit-sub-category');
	Route::any('/add-sub-category',[SubCategoriesController::class, 'addPage'])->name('admin.add-sub-category');

	#bookings
	Route::get('/orders',[OrdersController::class, 'getList'])->name('admin.orders');
    Route::any('/orders_paginate',[OrdersController::class, 'listPaginate'])->name('admin.orders_paginate');
    Route::any('/view-order/{row_id}',[OrdersController::class, 'viewPage'])->name('admin.view-order');
    Route::any('/update-order-status',[OrdersController::class, 'updateOrderStatus'])->name('admin.update-order-status');

	#inner pages
    Route::get('/inner-pages',[InnerPagesController::class, 'getList'])->name('admin.inner-pages');
    Route::any('/inner_pages_paginate',[InnerPagesController::class, 'listPaginate'])->name('admin.inner_pages_paginate');
	Route::any('/edit-inner-page/{row_id}',[InnerPagesController::class, 'editPage'])->name('admin.edit-inner-page');

	#enquiries
    Route::get('/enquiries',[EnquiriesController::class, 'getList'])->name('admin.enquiries');
    Route::any('/enquiries_paginate',[EnquiriesController::class, 'listPaginate'])->name('admin.enquiries_paginate');
    Route::any('/view-enquiry/{row_id}',[EnquiriesController::class, 'viewPage'])->name('admin.view-enquiry');

	#couponcode
    Route::get('/coupon-codes',[CouponCodesController::class, 'getList'])->name('admin.coupon-codes');
    Route::any('/coupon_codes_paginate',[CouponCodesController::class, 'listPaginate'])->name('admin.coupon_codes_paginate');
    Route::any('/add-coupon-code',[CouponCodesController::class, 'addPage'])->name('admin.add-coupon-code');
    Route::any('/edit-coupon-code/{row_id}',[CouponCodesController::class, 'editPage'])->name('admin.edit-coupon-code');

	#products
    Route::get('/products',[ProductsController::class, 'getList'])->name('admin.products');
    Route::any('/products_paginate',[ProductsController::class, 'listPaginate'])->name('admin.products_paginate');
    Route::any('/add-product',[ProductsController::class, 'addPage'])->name('admin.add-product');
    Route::any('/edit-product/{row_id}',[ProductsController::class, 'editPage'])->name('admin.edit-product');	
	Route::any('/upload-product-images',[ProductsController::class, 'uploadProductImages'])->name('admin.upload-product-images');
	Route::any('/update-product-title',[ProductsController::class, 'updateProductTitle'])->name('admin.update-product-title');
	Route::any('/update-product-file',[ProductsController::class, 'updateProductFile'])->name('admin.update-product-file');

	#ServicesController
    Route::get('/services',[ServicesController::class, 'getList'])->name('admin.services');
    Route::any('/services_paginate',[ServicesController::class, 'listPaginate'])->name('admin.services_paginate');
	Route::any('/edit-service/{row_id}',[ServicesController::class, 'editPage'])->name('admin.edit-service');
	Route::any('/add-service',[ServicesController::class, 'addPage'])->name('admin.add-service');
	
	#PackagesController
    Route::get('/packages',[PackagesController::class, 'getList'])->name('admin.packages');
    Route::any('/packages_paginate',[PackagesController::class, 'listPaginate'])->name('admin.packages_paginate');
	Route::any('/edit-package/{row_id}',[PackagesController::class, 'editPage'])->name('admin.edit-package');
	Route::any('/add-package',[PackagesController::class, 'addPage'])->name('admin.add-package');

    #memberships
    Route::get('/memberships',[MembershipController::class, 'getList'])->name('admin.memberships');
    Route::any('/memberships_paginate',[MembershipController::class, 'listPaginate'])->name('admin.memberships_paginate');
	Route::any('/edit-membership/{row_id}',[MembershipController::class, 'editPage'])->name('admin.edit-membership');
	Route::any('/add-membership',[MembershipController::class, 'addPage'])->name('admin.add-membership');
	
	#memberships request
    Route::get('/membership-request',[MembershipRequestController::class, 'getList'])->name('admin.membership.request');
    Route::any('/membership_request_paginate',[MembershipRequestController::class, 'listPaginate'])->name('admin.membership_request_paginate');
	Route::any('/edit-membership-request/{row_id}',[MembershipRequestController::class, 'editPage'])->name('admin.edit-membership-request');
	Route::any('/add-membership-request',[MembershipRequestController::class, 'addPage'])->name('admin.add-membership-request');
	Route::any('/generate-pdf',[MembershipRequestController::class, 'generatePdf'])->name('admin.generate-pdf');

    #memberships
    Route::get('/experience',[ExperienceController::class, 'getList'])->name('admin.experience');
    Route::any('/experience_paginate',[ExperienceController::class, 'listPaginate'])->name('admin.experience_paginate');
	Route::any('/edit-experience/{row_id}',[ExperienceController::class, 'editPage'])->name('admin.edit-experience');
	Route::any('/add-experience',[ExperienceController::class, 'addPage'])->name('admin.add-experience');
    
	#ajax
	Route::post('/change-product-status',[AjaxController::class, 'changeProductStatus'])->name('admin.change-product-status');
	Route::post('/change-status',[AjaxController::class, 'changeStatus'])->name('admin.change-status');
    Route::post('/delete-record',[AjaxController::class, 'deleteRecord'])->name('admin.delete-record');
    Route::post('/get-sub-category',[AjaxController::class, 'getSubCategories'])->name('admin.get-sub-category');
	

	#settings
    Route::get('/settings',[ProfileController::class, 'settings'])->name('admin.settings');
	Route::post('/save-setting',[ProfileController::class, 'saveSetting'])->name('admin.save-setting');


    #update profile
    Route::get('/update-profile',[ProfileController::class, 'updateProfile'])->name('admin.update-profile');
    Route::post('/save-profile',[ProfileController::class, 'saveProfile'])->name('admin.save-profile');


    #change password
    Route::get('/change-password',[ProfileController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/update-password',[ProfileController::class, 'updatePassword'])->name('admin.dashboard');

	

	#StaffController
    Route::get('/staff',[StaffController::class, 'getList'])->name('admin.staff');
    Route::any('/staff_paginate',[StaffController::class, 'listPaginate'])->name('admin.staff_paginate');
    Route::any('/add-staff',[StaffController::class, 'addPage'])->name('admin.add-staff');
    Route::any('/edit-staff/{row_id}',[StaffController::class, 'editPage'])->name('admin.edit-staff');	

    #Users
    Route::get('/users',[UsersController::class, 'getList'])->name('admin.users');
    Route::any('/users_paginate',[UsersController::class, 'listPaginate'])->name('admin.users_paginate');
    Route::any('/add-user',[UsersController::class, 'addPage'])->name('admin.add-user');
    Route::any('/edit-user/{row_id}',[UsersController::class, 'editPage'])->name('admin.edit-user');	

    #attendence
    Route::get('/attendence',[AttendenceController::class, 'getList'])->name('admin.attendence');
    Route::any('/attendence_paginate',[AttendenceController::class, 'listPaginate'])->name('admin.attendence_paginate');
    Route::any('/add-attendence',[AttendenceController::class, 'addPage'])->name('admin.add-attendence');
    Route::any('/edit-attendence/{row_id}',[AttendenceController::class, 'editPage'])->name('admin.edit-attendence');
    Route::any('/update-attendence',[AttendenceController::class, 'updateAttendance'])->name('admin.update-attendence');

    #Employee attendence    
    Route::get('/employee-attendence',[EmployeeAttendenceController::class, 'getList'])->name('admin.employee-attendence');
    Route::post('/save-attendence',[EmployeeAttendenceController::class, 'saveAttendence'])->name('admin.save-attendence');
    
});





Route::middleware('auth:sanctum')->get('/user', function (Request $request) {



    return $request->user();



});