<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AssigenTestController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\TestThemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;


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
    Route::resource('test', TestController::class);
    Route::resource('company', CompanyController::class);
    Route::resource('position', PositionController::class);
    Route::resource('test-theme', TestThemeController::class);
    Route::resource('test-type', TestTypeController::class);
    Route::resource('test-assign', AssigenTestController::class);
    Route::resource('test-assign', AssigenTestController::class);

    Route::get('/test-add/{id}', [TestController::class, 'createCustom'])->name('createCustom');

    Route::prefix('admin')->group(function () {
        Route::get('/testhistory/{id}', [AssigenTestController::class, 'testsHistoryShow'])->name('testhistoryshow');
        Route::get('/testhistory', [AssigenTestController::class, 'testsHistory'])->name('testhistory');
        Route::get('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('resetpassword');

    });

    Route::prefix('user')->group(function () {
        Route::get('/profile-edit', [AssigenTestController::class, 'testsHistoryShow'])->name('profileedit');
        Route::get('/test-assign', [AssigenTestController::class, 'userTestAssign'])->name('user.testassign');
        Route::get('/tests-history', [AssigenTestController::class, 'userTestsHistory'])->name('user.testhistory');
        Route::get('/tests-history/{id}', [AssigenTestController::class, 'userTestsHistoryShow'])->name('user.testhistoryshow');
        Route::get('/take-test/{id}', [AssigenTestController::class, 'takeTest'])->name('user.test.take');
        Route::post('/test-result/{id}', [AssigenTestController::class, 'saveResult'])->name('user.test.result');
        Route::get('/edit', [UserController::class, 'userEdit'])->name('user.edit');
        Route::post('/edit/{id}', [UserController::class, 'userChange'])->name('user.change');
        Route::get('/change-password', [UserController::class, 'changePassword'])->name('user.changepass');
        Route::post('/edit-password', [UserController::class, 'editUserPassword'])->name('user.editpass');

    });


    //Searches
    Route::get('/users-search/', [UserController::class, 'search'])->name('usersearch');

});


