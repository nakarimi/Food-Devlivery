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
        return $this->get_items($request['categoryID'] , $request['restaurantID'], $keyword = false);
    }

    public function get_single_restaurant_profile(Request $request) {
        
        $data['profile'] = Branch::select('id', 'business_type')->with('branchDetails:business_id,title,description,logo,contact,location')->where('id', $request['restaurantID'])->get();

        // Check if this restaurant is from customer's favorite.
        $count = DB::table('favorited_restaurants')->where('branch_id', $request['restaurantID'])->count();

        // add is_favorite to result.
        $data['profile'][0]->is_favorite = ($count > 0); 

        // List of all item categories indexed by main type. (title, id)
        $data['tabs'] = Category::get(['id', 'type', 'title'])->groupBy('type')->toArray(); 
        
        return $data;
    }

    public function search_foods_in_retaurant(Request $request) {
            
        return $this->get_items($category = false, $request['restaurantID'], $request['keyword']);
    }

    // Get list of restaurants based on the provided filters.
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

    // Get items of a restaurant based on the provided filters.
    public function get_items($category = false, $branch = false, $keyword = false) {
        
        $items = Item::select('id')->with('approvedItemDetails:item_id,title,description,thumbnail,price');

        if ($keyword) {
            $items = $items->wherehas(
                'approvedItemDetails', function ($query) use ($keyword) {
                $query->where('title','LIKE', "%$keyword%");
            });
        }

        if ($branch) {
            $items = $items->where('branch_id', $branch);
        }

        if ($category) {
            $items = $items->where('items.category_id', $category);
        }

        return $items->latest()->get();
    }
}
