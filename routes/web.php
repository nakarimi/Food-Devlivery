<?php

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




Auth::routes();

// For Logged in users.
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { return redirect('/admin');});

    Route::get('admin', 'App\Http\Controllers\Admin\AdminController@index');
    Route::resource('admin/roles', 'App\Http\Controllers\Admin\RolesController');
    Route::resource('admin/permissions', 'App\Http\Controllers\Admin\PermissionsController');
    Route::resource('admin/users', 'App\Http\Controllers\Admin\UsersController');
    Route::resource('admin/pages', 'App\Http\Controllers\Admin\PagesController');
    Route::resource('admin/activitylogs', 'App\Http\Controllers\Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('admin/settings', 'App\Http\Controllers\Admin\SettingsController');
    Route::get('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);


    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('branch', 'App\Http\Controllers\BranchController');
    Route::resource('commission', 'App\Http\Controllers\CommissionController');
    Route::resource('driver', 'App\Http\Controllers\DriverController');
    Route::resource('payment', 'App\Http\Controllers\PaymentController');

});

Route::resource('item', 'App\Http\Controllers\ItemController');