<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AssigenTestController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\TestThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome.index');
//    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('test', AssigenTestController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('position', PositionController::class);
    Route::resource('test-theme', TestThemeController::class);
    Route::resource('test-type', TestTypeController::class);
    Route::get('/test-add/{id}', [AssigenTestController::class, 'createCustom'])->name('createCustom');


});


