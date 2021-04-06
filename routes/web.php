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
    Route::get('admin/dashboard', 'App\Http\Controllers\DashboardsController@adminDashboard')->name('admin.dashboard');
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
    Route::resource('blockedCustomer', 'App\Http\Controllers\BlockCustomerController');
    Route::post('/backup-create','App\Http\Controllers\BackupController@create')->name('get-backup');
    Route::get('/backups','App\Http\Controllers\BackupController@index')->name('backups');
    Route::delete('/delete-backup/{name}', 'App\Http\Controllers\BackupController@destroy')->name('backup.destroy');
    Route::post('/download-backup/', 'App\Http\Controllers\BackupController@downloadBackup')->name('backup.download');
    Route::post('/approvePayment', 'App\Http\Controllers\PaymentController@approvePayment');
    Route::post('/rejectPayment', 'App\Http\Controllers\PaymentController@rejectPayment');

});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Restaurant)
|--------------------------------------------------------------------------
*/
Route::middleware(['restaurant'])->group(function () {

    Route::get('restaurant/dashboard', 'App\Http\Controllers\DashboardsController@restaurantDashboard')->name('restaurant.dashboard');

    Route::get('/pendingItems', 'App\Http\Controllers\ItemController@pendingItems')->name('items.pending');
    Route::get('/approvedItems', 'App\Http\Controllers\ItemController@approvedItems')->name('items.approved');
    Route::get('/rejectedItems', 'App\Http\Controllers\ItemController@rejectedItems')->name('items.rejected');
    Route::post('updateItemStockStatus', 'App\Http\Controllers\ItemController@updateItemStockStatus')->name('updateItemStockStatus');
    Route::get('paymentHistory', 'App\Http\Controllers\PaymentController@restaurantPayments')->name('paymentHistory');
    Route::get('paymentsCreate', 'App\Http\Controllers\PaymentController@restaurantPaymentsCreate');
    Route::post('saveRestaurantPayment', 'App\Http\Controllers\PaymentController@SaveRestaurantPayments');
    Route::resource('item', 'App\Http\Controllers\ItemController');
    Route::resource('menu', 'App\Http\Controllers\MenuController');
    Route::post('updateMenuStockStatus', 'App\Http\Controllers\MenuController@updateMenuStockStatus')->name('updateMenuStockStatus');
    Route::get('/profile', 'App\Http\Controllers\BranchController@restaurantProfile')->name('restaurant.profile');
    Route::resource('branch', 'App\Http\Controllers\BranchController');
    Route::post('updateOrderStatus', 'App\Http\Controllers\OrdersController@updateOrderStatus')->name('updateOrderStatus');
    Route::post('assignDriver', 'App\Http\Controllers\OrdersController@assignDriver')->name('assignDriver');
    Route::post('requestDelivery', 'App\Http\Controllers\OrdersController@requestDelivery')->name('requestDelivery');

    // Livewire Route for active orders.
    Route::get('/activeOrders', \App\Http\Livewire\ActiveOrder::class);

    Route::get('/waitingOrders', \App\Http\Livewire\WaitingOrder::class);

    Route::get('/order-history', 'App\Http\Controllers\OrdersController@orderHistory')->name('order.history');
    Route::resource('orders', 'App\Http\Controllers\OrdersController')->only([
        'edit', 'show', 'destroy', 'update'
    ]);;

    Route::post('blockCustomer', 'App\Http\Controllers\BlockCustomerController@store')->name('blockCustomer');
    Route::get('get_orders_by_status', 'App\Http\Controllers\DashboardsController@get_orders_by_status');
    Route::put('mark_read_notifications', [App\Http\Controllers\HomeController::class, 'markNotificationAsRead']);
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Driver)
|--------------------------------------------------------------------------
*/
Route::middleware(['driver'])->group(function () {
    Route::get('driver/dashboard', 'App\Http\Controllers\DashboardsController@driverDashboard')->name('driver.dashboard');
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Support)
|--------------------------------------------------------------------------
*/
Route::middleware(['support'])->group(function () {
    Route::get('support/dashboard', 'App\Http\Controllers\DashboardsController@supportDashboard')->name('support.dashboard');
});

/*
|--------------------------------------------------------------------------
| All routes in this part can access by (Admin, Customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['customer'])->group(function () {
    Route::get('customer/dashboard', 'App\Http\Controllers\DashboardsController@customerDashboard')->name('customer.dashboard');
});
