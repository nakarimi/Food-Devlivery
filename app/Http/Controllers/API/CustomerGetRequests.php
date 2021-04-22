<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Category;
use JWTAuth;

class CustomerGetRequests extends Controller
{

    public function home_page_data() {
        $data['desserts'] = Category::where('type', 'dessert')->get();
        $data['main_food'] = Category::where('type', 'main_food')->get();
        $data['latest_restaurants'] = $this->get_list_restaurants($all = false, $latest = true, $favorited = false, $customerID = false);
        $data['favorite_restaurants'] = $this->get_list_restaurants($all = false, $latest = false, $favorited = true, $customerID = JWTAuth::user()->id);
        
        return $data;
    }

    public function get_list_restaurant_food_of_single_category(Request $request) {
        
        $foods = Item::select('id')->with('approvedItemDetails:item_id,title,description,thumbnail')->where('items.branch_id', $request['restaurantID'])->where('items.category_id', $request['categoryID'])->get();
        return $foods;
    }

    public function get_single_restaurant_profile(Request $request) {
        return Branch::with('branchDetails')->where('id', $request['restaurantID'])->get();
    }

    public function get_list_restaurants($all = false, $latest = false, $favorited = false, $customerID = false) {
        
        $branches = DB::table('branches')
        ->join('branche_main_info', 'branches.id', '=', 'branche_main_info.business_id')
        ->where('branche_main_info.status', 'approved');

        if ($favorited) {
            $branches = $branches->join('favorited_restaurants', 'branches.id', '=', 'favorited_restaurants.branch_id')->where('favorited_restaurants.customer_id', $customerID);
        }
        else if ($latest) {
            $branches = $branches->orderBy('branches.created_at', 'desc');
        }

        return $branches->select('branches.id', 'branche_main_info.title', 'branche_main_info.description', 'branche_main_info.logo')->get();
    }
}
