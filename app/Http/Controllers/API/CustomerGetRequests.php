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
	public function branch_list(Request $request) {
        $type = $request['branch_type'];
        $all = $latest = $favorited = $customerID = false;

        switch ($type) {
            case 'favorited':
                $favorited = true;
                $customerID = $request['customer_id'];
                break;
            case 'latest':
                $latest = true;
                break;
            default:
                $all = true;
        }
        return $this->get_list_restaurants($all, $latest, $favorited, $customerID);
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

    public function get_list_restaurants($all = false, $latest = false, $favorited = false, $customerID = false) {
        
        $branches = DB::table('branches')
        ->join('branche_main_info', 'branches.id', '=', 'branche_main_info.business_id')
        ->where('branche_main_info.status', 'approved');

        if ($favorited) {
            $branches = $branches->join('favorited_restaurants', 'branches.id', '=', 'favorited_restaurants.branch_id')->where('favorited_restaurants.customer_id', $customerID);
        }

        $branches = $branches->select('branches.id', 'branche_main_info.title', 'branche_main_info.description', 'branche_main_info.logo')->get();

        return $branches;
    }
}
