<?php

use App\Models\Branch;
use App\Models\Item;
use \App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Driver;
use Carbon\Carbon;
use App\Models\DeliveryDetails;
use App\Models\Setting;

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
            switch($itemType) {
                case 'pending':
                    return $item->pendingItemDetails;
                break;
                case 'rejected':
                    return $item->rejectedItemDetails;
                break;
                default:
                    return $item->approvedItemDetails;
            }
        }
        return;
    }
}

/**
 * This function loads all items of the current user based on status.
 * Status can be an array so it will return multiple items of user.
 * 
 * @param $status
 * request contains the file object.
 * @param $userId
 * request contains the file object.
 * @param $count
 * request contains the file object.
 * @param $all
 * Load all items regardless of there status (available or N/A) for the list.
 *
 * @return a string which is name of the file with extension and address.
 *
 * */
if (!function_exists('loadUserItemsData')) {
    function loadUserItemsData($status, $userId = null, $count = false, $all = false)
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
        $items =  getUserItemsBasedOnStatus($branchIds, $status, $count, $all);
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

    function getUserItemsBasedOnStatus ($branchIds, $status, $count, $all = false){
        $item = Item::whereHas(
            'itemFullDetails', function ($query) use ($status) {
            $query->whereIn('details_status', $status);
        })->whereIn('branch_id',$branchIds);

        if (!$all) {
            $item = $item->where('status', '1');
        }
        if ($count){
            $item = $item->count();
        }
        else {
            $item = $item->get();
        }
        return $item;
    }
}

// This function get specific  user branches and returns user menus.
if (!function_exists('loadUserMenuData')){
    function loadUserMenuData($userId, $statusCheck = false){
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }

        // Get user Items.
        $menu =  getUserMenus($branchIds, $statusCheck);
        return $menu;
    }
}

// This will return menus based on branch ids.
if (!function_exists('getUserMenus')){
    function getUserMenus ($branchIds, $statusCheck = false){
        $menu = Menu::whereIn('branch_id',$branchIds);

        if ($statusCheck) {
            $menu = $menu->where('status', '1');
        }

        $menu = $menu->latest()->paginate(10);
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
    function loadUserAllOrders ($userId, $status, $perPage = NULL){
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }
        $orders = Order::whereIn('branch_id', $branchIds)->whereIn('status', $status)->with('customer.blockedCustomer');

        if ($perPage) {
            $orders = $orders->latest()->paginate($perPage);
        }
        else {
            $orders = $orders->latest()->get();
        }

        return $orders;
    }
}

if (!function_exists('get_branch_details')) {
    /**
     * Return a branch latest details.
     * */
    function get_branch_details($branch, $branchType = 'approved') {

        if($branch) {
            switch($branchType) {
                case 'pending':
                    return $branch->pendingBranchDetails;
                break;
                case 'rejected':
                    return $branch->rejectedBranchDetails;
                break;
                default:
                    return $branch->branchDetails;
            }
        }
        return;
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
    function get_orders($type, $request, $realTime = false, $keyword = null) {
        // Order lists based on different status. active([Pending, Accept, Processing, Delivery]) and history ([completed, Canceled])
        $status = [];
        switch($type) {
            case 'history':
                $status = ['completed', 'canceld', 'reject'];
            break;
            case 'active-orders':
                $status = ['pending', 'processing', 'delivered'];
            break;
            default:
            $status = [];
        };

        $drivers = Driver::all();
        $perPage = 25;

        if ($type == 'waiting-orders') {
            $status = ['delivered'];
        }

        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = Auth::user()->id;
            $orders = loadUserAllOrders($userId, $status, $perPage);
            if ($realTime) {
                return view('livewire.restaurant.active-orders', compact('orders', 'drivers'))->extends('dashboards.restaurant.layouts.master');
            }
            return view('dashboards.restaurant.orders.index', compact('orders'));
        }

        // For real time data, datatable search is enogh.
        $keyword = ($request) ? $request->get('search') : $keyword;
        if ($type != 'waiting-orders' && !empty($keyword)) {
            $orders = Order::whereIn('status', $status)->wherehas(
                'branchDetails', function ($query) use ($keyword) {
                $query->where('title','LIKE', "%$keyword%");
            })->orwhere('title', 'LIKE', "%$keyword%")->whereIn('status',$status)
                ->latest()->paginate($perPage);
        }
        elseif ($type == 'waiting-orders'){
            $orders = get_waiting_orders($keyword, $perPage, false);
        }
        else {
            $orders = Order::whereIn('status', $status)->latest()->paginate($perPage);
        }

        // Real time template are in livewire dir.
        if ($realTime) {
            return view('livewire.waiting-orders', compact('orders', 'drivers'));
        }

        // Order history routes are using main template.
        return view('order.orders.index', compact('orders', 'drivers'));
    }
}

// Send notification based on user Ids and message we provide.
if (!function_exists('send_notification')){
    function send_notification(array $notifyUsers, $userId, $message) {
        $notifyUsers = User::whereIn('id', $notifyUsers)->get();

        for ($i=0; $i < sizeof($notifyUsers) ; $i++) { 
            event(new \App\Events\NotificationEvent('Notification', $notifyUsers[$i]));
        }
        
        \Illuminate\Support\Facades\Notification::send($notifyUsers, new \App\Notifications\UpdateNotification($message, $userId));
    }
}

// This will return menu items for views.
if (!function_exists('update_order')){
    function update_order ($requestData, $id, $api = false) {

        try {
            DB::transaction(function () use ($requestData, $id){
                $deliver_update = false;
                $requestData['has_delivery'] = 0;
        
                if ($requestData['delivery_type'] != 'self') {
                    $requestData['has_delivery'] = 1;
                    $deliver_update = true;
                }
        
                $order = Order::findOrFail($id);
        
        
                $orderData = [
                    'branch_id' => $requestData['branch_id'],
                    'customer_id' => $requestData['customer_id'],
                    'has_delivery' => $requestData['has_delivery'],
                    'title' => $requestData['title'],
                    // 'commission_value' => $requestData['commission_value'],
                    'status' => $requestData['status'],
                    'note' => $requestData['note'],
                    'reciever_phone' => $requestData['reciever_phone'],
                    'contents' => $requestData['contents'],
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];
    
                $order->update($orderData);
    
                if ($deliver_update) {
                    $updateDeliveryDetails = [
                        'order_id' => $id,
                        'delivery_type' => $requestData['delivery_type'],
                        'delivery_adress' => $requestData['delivery_adress'],
                    ];
                    
                    // Update delivery details.
                    $result = DeliveryDetails::updateOrCreate(['order_id' => $id], $updateDeliveryDetails);
                }
                
            });
        } catch (\Throwable $th) {
            dd($th);
        }
        


        event(new \App\Events\UpdateEvent('Order Updated!'));
        return redirect()->back()->with('flash_message', 'Order updated!');
            
    }
}

 /**
 * Validate request inputs.
 *
 * @param object $request
 */
if (!function_exists('validateOrderInputs')){
    function validateOrderInputs($request) {
        $validator = Validator::make($request->all(),
            [
                'branch_id' => 'required|integer',
                'customer_id' => 'required|integer',
                'delivery_type' => 'required',
                'total' => 'required|integer',
                'commission_value' => 'required',
                'status' => 'required',
                'reciever_phone' => 'required',
                'contents' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
    }
}

// Calculate the percentage between two numbers.
if (!function_exists('calculate_percentage')){
    function calculate_percentage($oldValue, $newValue){
        if ($oldValue == 0) {
            $oldValue++;
            $newValue++;
        }
        $percentage = (($newValue - $oldValue) / $oldValue) * 100;
        return round($percentage, 2);
    }
}

// Format a percentage number to show on blade.
if (!function_exists('format_percentage')){
    function format_percentage($percentage){
        if ($percentage >= 1){
            $percentage = ['+'.$percentage, 'text-success'];
        }
        else {
            $percentage = [$percentage, 'text-danger'];
        }
        return $percentage;
    }
}

// Format a percentage number to show on blade.
if (!function_exists('translate_status')){
    function translate_status($status){
        switch($status) {
            case 'pending':
                return 'انتظار';
            break;
            case 'processing':
                return 'آماده شدن';
            break;
            case 'reject':
                return 'رد شده';
            break;
            case 'delivered':
                return 'ارسال شده';
            break;
            case 'completed':
                return 'تکمیل شده';
            break;
            default:
            return 'لغو شده';
        }
    }
}

// Format promissed date to a farsi readable date.
if (!function_exists('get_promissed_date')){
    function get_promissed_date($date){
        if ($date){
            Carbon::setLocale('fa');             
            return Carbon::parse($date)->diffForHumans();
        }
        
        return '';
    }
}

// Check if an order is late, add a class for css style applying.
if (!function_exists('is_order_late')){
    function is_order_late($date, $status){
        $completed_status = ['reject', 'canceld', 'completed'];

        return (!in_array($status, $completed_status) && Carbon::parse($date)->isPast()) ? 'order_is_late' : '';
    }
}

// Update table column with boolean values.
if (!function_exists('columnToggleUpdate')){
    function columnToggleUpdate($table, $column, $record_id){
        DB::statement("UPDATE $table SET $column = 1 - status WHERE id = $record_id");
    }
}

// Update table column with boolean values.
if (!function_exists('get_customer_status')){
    function get_customer_status($customer_id){
        
        $blockCustomerStatus = DB::table('block_customers')->where('customer_id', '=', $customer_id)->value('status');

        return ($blockCustomerStatus) ?: 'Notblocked';
        
    }
}

// Update table column with boolean values.
if (!function_exists('get_waiting_orders')){
    function get_waiting_orders($keyword, $perPage, $count = false){
        // Get orders from 10 minutes ago.
        $timeOffSet = Carbon::now()->subMinutes(1)->toDateTimeString();

        $orders = Order::where(function ($query) use ($timeOffSet, $keyword) {
            // Get orders that created 2 mins ago and still not responsed by restaurant.
            $query->where('status', 'pending')->where('orders.created_at', '<', $timeOffSet)->whereHas('branchDetails', function ($sub) use ($keyword) {
                    $sub->where('title','LIKE', "%".$keyword."%");
                });
        })->orwhere(function ( $query ) use ($keyword) {
            // Get orders that are assigned to company to delivery and yet have no driver assigned to them.
            $query->whereHas('deliveryDetails', function ($subquery) {
            $subquery->where('delivery_type', 'company')->whereNull('driver_id');
        })->where('status', '<>' ,'reject')->whereHas('branchDetails', function ($sub) use ($keyword) {
            $sub->where('title','LIKE', "%".$keyword."%");
        });
        })->latest();
        
        return ($count) ?  $orders->count() : $orders->paginate($perPage);
    }
}
        
// Update table column with boolean values.
if (!function_exists('get_current_branch_info')){
    function get_current_branch_info(){
        $userId = Auth::user()->id;
        return Branch::where('user_id', $userId)->first() ?: NULL;
    }
}

// get setting from configurations.
if (!function_exists('setting_config')){
    function setting_config($key){
        return Setting::where('key', $key)->pluck('value')->toArray();
    }
}

