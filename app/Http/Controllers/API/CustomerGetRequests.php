<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Category;
use App\Models\Order;
use JWTAuth;
// use Validator;

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
        
        $data['profile'] = Branch::select('id', 'business_type', 'status')->with('branchDetails:business_id,title,description,logo,contact,location,banner')->where('id', $request['restaurantID'])->get();

        // Check if this restaurant is from customer's favorite.
        $count = DB::table('favorited_restaurants')->where('branch_id', $request['restaurantID'])->count();

        // add is_favorite to result.
        $data['profile'][0]->is_favorite = ($count > 0); 

        // List of all items of a restaurant.
        $data['items'] = Item::select('id', 'category_id')->with('approvedItemDetails:item_id,title,description,thumbnail,price')->where('branch_id', $request['restaurantID'])->get()->toArray();

        // These categories should be those which actually restaruant has item for them.
        $category_ids = [];
        foreach ($data['items'] as $row) {
            $category_ids[] = $row['category_id'];
        }
        
        // List of all item categories indexed by main type. (title, id).
        $data['tabs'] = Category::get(['id', 'type', 'title'])->whereIn('id', array_unique($category_ids))->groupBy('type')->toArray();

        $customerID = JWTAuth::user()->id; // Get current customer id from JWT Authentication.

        // List of old item purchased by this customer from this restaurant.
        $data['old_purchases'] = $this->get_old_purchases($request['restaurantID'], $customerID); 
        
        return $data;
    }

    public function search_foods_in_retaurant(Request $request) {
            
        return $this->get_items($category = false, $request['restaurantID'], $request['keyword']);
    }

    // Get list of restaurants based on the provided filters.
    public function get_list_restaurants($all = false, $latest = false, $favorited = false, $customerID = false, $keyword = false) {
        
        $branches = DB::table('branches')
        ->join('branche_main_info', 'branches.id', '=', 'branche_main_info.business_id')
        ->where('branche_main_info.status', 'approved');

        if ($favorited) {
            $branches = $branches->join('favorited_restaurants', 'branches.id', '=', 'favorited_restaurants.branch_id')->where('favorited_restaurants.customer_id', $customerID);
        }
        else if ($latest) {
            $branches = $branches->orderBy('branches.created_at', 'desc');
        }

        if ($keyword) {
            // Since different column is needed, return is different.
            return $branches->where('branche_main_info.title','LIKE', "%$keyword%")->select('branches.id', 'branche_main_info.title')->get();
        }

        return $branches->select('branches.id', 'branche_main_info.title', 'branche_main_info.description', 'branche_main_info.logo as thumbnail')->get();
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

    // Get items of a restaurant based on the provided filters.
    public function search_items($keyword = false) {
        
        $items = Item::select('id')->with('approvedItemDetails:item_id,title,description,thumbnail,price');

        if ($keyword) {
            $items = $items->wherehas(
                'approvedItemDetails', function ($query) use ($keyword) {
                $query->where('title','LIKE', "%$keyword%");
            });
        }

        return $items->paginate(5);
    }

    // This helper search for available items and restuarant and generate array with tow sections based on provided keyword.
    public function home_page_general_search(Request $request) {
        
        $data['items'] = $this->search_items($request['keyword']);

        $data['branches'] = $this->get_list_restaurants($all = false, $latest = false, $favorited = false, $customerID = false, $request['keyword']);

        return $data;
    }

    // List of old item purchased by this customer from this restaurant.
    public function get_old_purchases($branch, $customerID) {

        $old_orders = Order::where('customer_id', $customerID)->where('branch_id', $branch)->get(['contents'])->toArray();

        $item_ids = [];

        // Go through the orders and get items from its content,
        foreach ($old_orders as $order) {

            $items = json_decode($order['contents']);

            // Go through each content and collect items.
            foreach ($items->contents as $key => $item) {
                $item = reset($item); 
                $item_ids[] = $item->item_id;
            }

        }

        return Item::select('id')->with('approvedItemDetails:item_id,title,description,thumbnail,price')->whereIn('id', array_unique($item_ids))->get()->toArray();
    }

    public function active_orders(Request $request) {
        return $this->orders_list(['pending', 'processing', 'delivered'], JWTAuth::user()->id);
    }

    public function order_history(Request $request) {
        return $this->orders_list(['completed', 'canceld', 'reject'], JWTAuth::user()->id);
    }

    public function orders_list($status, $customerID) {
        
        $orders = [];

        $ordersList = Order::whereIn('status', $status)->where('customer_id', $customerID)->latest()->get();


        foreach ($ordersList as $order) {
            $data = []; 
            $data['id'] = $order->id;
            $data['price'] = $order->total;
            $data['reciever_phone'] = $order->reciever_phone;
            $data['delivery_address'] = $order->deliveryDetails->delivery_address;
            $data['restaurant_title'] = $order->branchDetails->title;
            $data['restaurant_logo'] = $order->branchDetails->logo;
            $data['restaurant_location'] = $order->branchDetails->location;
            $data['created_at'] = $order->created_at;
            $data['completed_time'] = $order->timeDetails->completed_time ?? NULL;
            $data['promissed_time'] = $order->timeDetails->promissed_time ?? NULL;
            $data['contents'] = show_order_itmes($order->contents, true);

            $orders[] = $data;
        }

        return $orders;
    }


}
