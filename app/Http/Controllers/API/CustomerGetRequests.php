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

class CustomerGetRequests extends Controller
{
	public function branch_list() {

            $branches['latest'] = DB::table('branches')
                ->join('branche_main_info', 'branches.id', '=', 'branche_main_info.business_id')
                ->where('branche_main_info.status', 'approved')
                ->select('branches.id', 'branche_main_info.title', 'branche_main_info.description', 'branche_main_info.logo')
            ->get();

            $branches['favorite '] = DB::table('branches')
                ->join('branche_main_info', 'branches.id', '=', 'branche_main_info.business_id')
                ->where('branche_main_info.status', 'approved')
                ->select('branches.id', 'branche_main_info.title', 'branche_main_info.description', 'branche_main_info.logo')
            ->get();

        return $branches;
    }

    public function get_list_restaurant_food_of_single_category(Request $request) {
        $foods = Item::select('id')->with('approvedItemDetails:item_id,title,description,thumbnail')->where('items.branch_id', $request['restaurantID'])->where('items.category_id', $request['categoryID'])->get();
        return $foods;
    }

    public function get_list_of_desserts() {
        return Category::where('type', 'dessert')->get();
    }

    public function get_list_of_main_foods() {
        return Category::where('type', 'main_food')->get();
    }

    public function get_list_of_newest_restaurants() {
        return Branch::select('id')->with('branchDetails:business_id,title,description,logo')->orderBy('created_at', 'desc')->get();
    }

    public function get_single_restaurant_profile(Request $request) {
        return Branch::with('branchDetails')->where('id', $request['restaurantID'])->get();
    }
}
