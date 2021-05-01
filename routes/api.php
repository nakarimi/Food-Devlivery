<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\JwtAuthController;
use App\Http\Controllers\API\CustomerGetRequests;
use App\Http\Controllers\API\CustomerPostRequests;
use App\Http\Controllers\API\DriverRequests;
use App\Http\Controllers\API\BranchRequests;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [JwtAuthController::class, 'login']);
    // Route::post('/register', [JwtAuthController::class, 'register']);
    Route::post('/customer-signup', [JwtAuthController::class, 'customer_signup']);
    Route::post('/customer-signin', [JwtAuthController::class, 'customer_signin']);
    Route::post('/logout', [JwtAuthController::class, 'logout']);
    Route::post('/refresh', [JwtAuthController::class, 'refresh']);    
});

Route::group([
    'middleware' => 'jwt.verify',
    'prefix' => 'customer'

], function ($router) {
    // All the bellow routes need token authentication.
    Route::post('/submit-new-order', [CustomerPostRequests::class, 'submit_new_order']);
    // Route::post('/signup', [CustomerPostRequests::class, 'customer_signup']);
    // Route::post('/shipping-address', [CustomerPostRequests::class, 'customer_shipping_address']);
    // Route::post('/verify-phone', [CustomerPostRequests::class, 'customer_verify_phone']); // This route called before generating token, so doesn't need middleware.
    Route::post('/update-order', [CustomerPostRequests::class, 'update_order']);
    Route::get('/home-page-data', [CustomerGetRequests::class, 'home_page_data']);
    Route::get('/restaurnt-food-list-signle-category', [CustomerGetRequests::class, 'get_list_restaurant_food_of_single_category']);
    Route::get('/get-single-restaurant-profile', [CustomerGetRequests::class, 'get_single_restaurant_profile']);
    Route::get('/search-foods-in-retaurant', [CustomerGetRequests::class, 'search_foods_in_retaurant']);
    Route::get('/home-page-general-search', [CustomerGetRequests::class, 'home_page_general_search']);
    Route::get('/active-orders', [CustomerGetRequests::class, 'active_orders']);
    Route::get('/order-history', [CustomerGetRequests::class, 'order_history']);

});

Route::group([
    'middleware' => 'jwt.verify',
    'prefix' => 'driver'

], function ($router) {
    // Route::get('/check', [DriverRequests::class, 'check']);
    Route::get('/new-orders-list', [DriverRequests::class, 'new_orders_list']);
    Route::get('/my-orders', [DriverRequests::class, 'my_orders']);
    Route::post('/pick-order', [DriverRequests::class, 'pick_order']); 
});

Route::group([
    'middleware' => 'jwt.verify',
    'prefix' => 'branch'

], function ($router) {
    Route::get('/check', [BranchRequests::class, 'check']);    
});