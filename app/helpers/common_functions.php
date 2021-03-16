<?php

use App\Models\Branch;
use App\Models\Item;
use \App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Driver;
use Carbon\Carbon;

if (!function_exists('save_file')) {
     /**
     * Store the image and return it's address.
     *
     * @param \Illuminate\Http\Response $request
     * request contains the file object.
     *
     * @return a string which is name of the file with extension and address.
     *
     * */
    function save_file(Request $request) {
        // Handle File Upload
        if($request->file('logo')) {

            // Get filename with extension
            $filenameWithExt = $request->file('logo')->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $request->file('logo')->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;

            // Upload Image
            $path = $request->file('logo')->storeAs('/public/profile_images', $fileNameToStore);

        }
        else {
            $fileNameToStore = 'noimage.jpg';
        }

        return $fileNameToStore;
    }
}

if (!function_exists('get_role')) {
     /**
     * Return user role name.
     * */
    function get_role() {
        // Handle File Upload
       $role =  auth()->user()->role->name;
        return $role;
    }
}

if (!function_exists('get_item_details')) {
    /**
     * Return an item latest details.
     * */
    function get_item_details($item, $itemType = 'approved') {
        if($item) {
            return ($itemType == 'approved') ? $item->approvedItemDetails : $item->pendingItemDetails;
        }
        return;
    }
}

// This function loads all items of the current user based on status.
// Status can be an array so it will return multiple items of user.
if (!function_exists('loadUserItemsData')) {
    function loadUserItemsData($status, $userId = null)
    {
        if ($userId == null){
            $userId = auth()->user()->id;
        }
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }

        // Get user Items.
        $items =  getUserItemsBasedOnStatus($branchIds, $status);
        return $items;
    }
}

// This will return user branches based on user id.
if (!function_exists('getUserBranches')){

    function getUserBranches($id)
    {
        $branches = Branch::where('user_id', $id)->get();
        return $branches;
    }
}

// This will return items based on status one or multiple status.
if (!function_exists('getUserItemsBasedOnStatus')){

    function getUserItemsBasedOnStatus ($branchIds, $status){
        $item = Item::whereHas(
            'itemFullDetails', function ($query) use ($status) {
            $query->whereIn('details_status', $status);
        })->whereIn('branch_id',$branchIds)->get();
        return $item;
    }
}

// This function get specific  user branches and returns user menus.
if (!function_exists('loadUserMenuData')){
    function loadUserMenuData($userId){
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }

        // Get user Items.
        $menu =  getUserMenus($branchIds);
        return $menu;
    }
}

// This will return menus based on branch ids.
if (!function_exists('getUserMenus')){
    function getUserMenus ($branchIds){
        $menu = Menu::whereIn('branch_id',$branchIds)->where('status', '1')->latest()->paginate(10);
        return $menu;
    }
}


// This will return menus based on branch ids.
if (!function_exists('getBranchesBasedOnStatus')){
    function getBranchesBasedOnStatus ($status){
        $branches = Branch::whereHas(
            'branchFullDetails', function ($query) use ($status) {
            $query->where('status', '=', $status);
        })->latest()->paginate(10);
        return $branches;
    }
}

// This function abort the process for the role we pass.
if (!function_exists('abortUrlFor')){
    function abortUrlFor ($role = null, $userId = null, $branchId = null){
        if ($role != null){
            if (get_role() == $role){
                abort(404);
            }
        }
        elseif($userId != null && $branchId != null){
            if ($userId != $branchId){
                abort(404);
            }
        }
    }
}

// This will return orders based on branch ids of a user.
if (!function_exists('loadUserAllOrders')){
    function loadUserAllOrders ($userId, $status){
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }
        $orders = \App\Models\Order::whereIn('branch_id', $branchIds)->whereIn('status', $status)->with('customer.blockedCustomer')->latest()->get();
        return $orders;
    }
}

if (!function_exists('get_branch_details')) {
    /**
     * Return a branch latest details.
     * */
    function get_branch_details($branch, $branchType = 'approved') {
        return ($branchType == 'approved') ? $branch->branchDetails : $branch->pendingBranchDetails;
    }
}

// This will return order items for views.
if (!function_exists('show_order_itmes')){
    function show_order_itmes ($items){

        // Open html warapper for list of items.
        $output = "<span class='order_content_list'><ul>";

        $items = json_decode($items);

        $items = $items->contents;

        for ($k=0; $k < count($items); $k++) {

            // Create the correct format of key.
            $key = 'item_'.($k + 1);

            // Get each item as single item.
            $item = $items[$k]->$key;

            // Load items data.
            $itemRecord = Item::with('approvedItemDetails')->where('id', $item->item_id)->first();

            // If item does not exist.
            if (!$itemRecord) {
                continue;
            }

            // Set title.
            $title =  $itemRecord->approvedItemDetails->title;

            $output .= "<li>$title, $item->count</li>";
        }
        // Close html Wrapper.
        $output .= "</ul></span>";

        return $output;
    }
}

// This will check if item is assigned for menu.
if (!function_exists('select_item_logic')){

    function select_item_logic($menu_items, $id){

        $item_ids = (array) json_decode($menu_items);

        if (in_array($id, $item_ids)) {
            return 'selected="selected"';
        }
    }
}

// This will return menu items for views.
if (!function_exists('show_menu_itmes')){
    function show_menu_itmes ($items){

        $itemIDs = json_decode($items);
        $items = Item::whereIn('id', $itemIDs)->get();

        $allItems = [];
        foreach($items as $item) {
            $allItems[] = '<a href="/item/'.$item->id.'">'.$item->approvedItemDetails->title.'</a>';
        }
        $allItems = implode(", ", $allItems);
        
        return "<p>$allItems</p>";
    }
}


// General function to get orders, based on the provided params.
if (!function_exists('get_orders')){
    function get_orders($type, $request, $realTime = false) {
        // Order lists based on different status. active([Pending, Accept, Processing, Delivery]) and history ([completed, Canceled])
        $status = [];
        switch($type) {
            case 'history':
                $status = ['completed', 'canceld'];
            break;
            case 'active-orders':
                $status = ['pending', 'approved', 'reject', 'processing', 'delivered'];
            break;
            default:
            $status = [];
        };

        $drivers = Driver::all();

        if ($type == 'waiting-orders') {
            $status = ['delivered'];
        }
    
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = Auth::user()->id;
            $orders = loadUserAllOrders($userId, $status);
            if ($realTime) {
                return view('livewire.restaurant.active-orders', compact('orders', 'drivers'))->extends('dashboards.restaurant.layouts.master');
            }
            return view('dashboards.restaurant.orders.index', compact('orders'));
        }
    
        $perPage = 10;

        // For real time data, datatable search is enogh.
        // Todo: dynamic search is needed for amdin section. 
        $keyword = ($request) ? $request->get('search') : NULL;
        if (!empty($keyword)) {
            $orders = Order::whereIn('status', $status)->wherehas(
                'branchDetails', function ($query) use ($keyword) {
                $query->where('title','LIKE', "%$keyword%");
            })->orwhere('title', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        }
        elseif ($type == 'waiting-orders'){
            // Get orders from 10 minutes ago.
            $timeOffSet = Carbon::now()->subMinutes(1)->toDateTimeString();
            $orders = Order::where(function ($query) use ($timeOffSet) {
                $query->where('status', 'pending')->where('orders.created_at', '<', $timeOffSet);
            })->orwhere(function ( $query ) {
                $query->whereHas('deliveryDetails', function ($subquery) {
                $subquery->where('delivery_type', 'company')->whereNull('driver_id');
            });
            })->latest()->paginate($perPage);
        }
        else {
            $orders = Order::whereIn('status', $status)->latest()->paginate($perPage);
        }

        // Real time template are in livewire dir.
        if ($realTime) {
            return ($type == 'waiting-orders') ? view('livewire.waiting-orders', compact('orders', 'drivers')) : view('livewire.active-order', compact('orders', 'drivers'));
        }
        
        // Order history routes are using main template.
        return view('order.orders.index', compact('orders', 'drivers'));
    }
}
