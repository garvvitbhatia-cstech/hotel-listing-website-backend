<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\UsersController;

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

Route::prefix('api')->group(function () {

	Route::any('/blogs/list',[HomePageController::class, 'blogList']);
	Route::any('/blog/get',[HomePageController::class, 'blogGet']);
	Route::any('/page/data/get',[HomePageController::class, 'pageDataGet']);
	Route::any('/property/list',[HomePageController::class, 'propertyList']);
	Route::any('/contact/save',[HomePageController::class, 'contactSave']);
	Route::any('/country/list',[HomePageController::class, 'countryList']);
	Route::any('/packages/list',[HomePageController::class, 'packageList']);
	Route::any('/b2b/list',[HomePageController::class, 'b2bList']);
	Route::any('/latest/property/list',[HomePageController::class, 'latestPropertyList']);
	Route::any('/property/get',[HomePageController::class, 'propertyGet']);
	Route::any('/testimonials/list',[HomePageController::class, 'testimonialsList']);
	Route::any('/membership/save',[HomePageController::class, 'membershipSave']);
	Route::any('/popular-search',[HomePageController::class, 'popularSearch']);
	
	Route::any('/register',[UsersController::class, 'register']);
	Route::any('/login',[UsersController::class, 'login']);

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();

});

