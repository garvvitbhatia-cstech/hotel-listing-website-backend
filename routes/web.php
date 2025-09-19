<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagesController;

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\Admin\AdminController;

/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/

/*Route::get('/', function () {

    return view('welcome');

});*/


//Route::get('/',[PagesController::class, 'index'])->name('pages.index');
Route::get('/',[AdminController::class, 'login'])->name('admin.login');


Route::post('/add-newsletter',[AjaxController::class, 'addNewsletter'])->name('pages.add-newsletter');

Route::post('/add-enquiry',[AjaxController::class, 'addEnquiry'])->name('pages.add-enquiry');



require "api.php";
require "admin.php";
require "export.php";
require "import.php";