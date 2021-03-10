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


Auth::routes([
    'register' => false
]);

// For Logged in users.
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () { return redirect('/login');});
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['admin'])->group(function () {
    Route::get('admin/dashboard', 'App\Http\Controllers\Admin\AdminController@index')->name('admin.dashboard');
    Route::resource('admin/users', 'App\Http\Controllers\Admin\UsersController');
    Route::resource('admin/activitylogs', 'App\Http\Controllers\Admin\ActivityLogsController')->only([
        'index', 'show', 'destroy'
    ]);
    Route::resource('admin/settings', 'App\Http\Controllers\Admin\SettingsController');
    Route::get('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@getGenerator']);
    Route::post('admin/generator', ['uses' => '\Appzcoder\LaravelAdmin\Controllers\ProcessController@postGenerator']);

    Route::resource('commission', 'App\Http\Controllers\CommissionController');
    Route::resource('driver', 'App\Http\Controllers\DriverController');
    Route::resource('payment', 'App\Http\Controllers\PaymentController');
    Route::post('/approveItem', 'App\Http\Controllers\ItemController@approveItem');
    Route::post('/rejectItem', 'App\Http\Controllers\ItemController@rejectItem');
    Route::post('/approveBranch', 'App\Http\Controllers\BranchController@approveBranch');
    Route::post('/rejectBranch', 'App\Http\Controllers\BranchController@rejectBranch');
    Route::resource('category', 'App\Http\Controllers\CategoryController');
    Route::get('/pendingBranches', 'App\Http\Controllers\BranchController@pendingBranches')->name('branches.pending');
    Route::get('/approvedBranches', 'App\Http\Controllers\BranchController@approvedBranches')->name('branches.approved');
    Route::get('/loadItemsBasedOnBranch', 'App\Http\Controllers\MenuController@loadItemsBasedOnBranch');
    Route::put('/deactiveUser/{id}', 'App\Http\Controllers\Admin\UsersController@deactiveUser');
    Route::put('/activateUser/{id}', 'App\Http\Controllers\Admin\UsersController@activateUser');


});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Restaurant)
|--------------------------------------------------------------------------
*/
Route::middleware(['restaurant'])->group(function () {

    Route::get('restaurant/dashboard', function (){
        return view('dashboards.restaurant.dashboard');
    })->name('restaurant.dashboard');

    Route::get('/pendingItems', 'App\Http\Controllers\ItemController@pendingItems')->name('items.pending');
    Route::get('/approvedItems', 'App\Http\Controllers\ItemController@approvedItems')->name('items.approved');
    Route::resource('item', 'App\Http\Controllers\ItemController');
    Route::resource('menu', 'App\Http\Controllers\MenuController');
    Route::get('/profile', 'App\Http\Controllers\BranchController@restaurantProfile')->name('restaurant.profile');
    Route::resource('branch', 'App\Http\Controllers\BranchController');
    Route::post('updateOrderStatus', 'App\Http\Controllers\OrdersController@updateOrderStatus')->name('updateOrderStatus');
    Route::resource('orders', 'App\Http\Controllers\OrdersController');
    Route::post('blockCustomer', 'App\Http\Controllers\BlockCustomerController@store')->name('blockCustomer');
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Driver)
|--------------------------------------------------------------------------
*/
Route::middleware(['driver'])->group(function () {
    Route::get('driver/dashboard', function (){
        return view('dashboards.driver.dashboard');
    })->name('driver.dashboard');
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Support)
|--------------------------------------------------------------------------
*/
Route::middleware(['support'])->group(function () {
    Route::get('support/dashboard', function (){
        return view('dashboards.support.dashboard');
    })->name('support.dashboard');
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['customer'])->group(function () {
    Route::get('customer/dashboard', function (){
        return view('dashboards.customer.dashboard');
    })->name('customer.dashboard');
});
