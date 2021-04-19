<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;

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

        // Original query.
        // DB::table('items')
        //     ->join('item_details', 'items.id', '=', 'item_details.item_id')
        //     ->where('item_details.details_status', 'approved')
        //     ->where('items.branch_id', $restaurantID)
        //     ->where('items.category_id', $categoryID)
        //     ->select('items.id', 'item_details.title', 'item_details.description', 'item_details.thumbnail')
        // ->get();        

    return $foods;
}
}
