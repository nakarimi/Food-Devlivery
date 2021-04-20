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
    Route::post('/register', [JwtAuthController::class, 'register']);
    Route::post('/logout', [JwtAuthController::class, 'logout']);
    Route::post('/refresh', [JwtAuthController::class, 'refresh']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'customer'

], function ($router) {
    Route::post('/subit-new-order', [CustomerPostRequests::class, 'submit_new_order']);
    Route::post('/update-order', [CustomerPostRequests::class, 'update_order']);
    Route::get('/branch-list', [CustomerGetRequests::class, 'branch_list']);
    Route::get('/restaurnt-food-list-signle-category', [CustomerGetRequests::class, 'get_list_restaurant_food_of_single_category']);
    Route::get('/get-list-of-desserts', [CustomerGetRequests::class, 'get_list_of_desserts']);
    Route::get('/get-list-of-main-foods', [CustomerGetRequests::class, 'get_list_of_main_foods']);
    Route::get('/get-list-newest-restaurants', [CustomerGetRequests::class, 'get_list_of_newest_restaurants']);
    Route::get('/get-single-restaurant-profile', [CustomerGetRequests::class, 'get_single_restaurant_profile']);
    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'driver'

], function ($router) {
    Route::get('/check', [DriverRequests::class, 'check']);    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'branch'

], function ($router) {
    Route::get('/check', [BranchRequests::class, 'check']);    
});