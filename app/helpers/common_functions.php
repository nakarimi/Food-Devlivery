<?php

use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use \App\Models\Menu;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Driver;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\DeliveryDetails;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Notification;

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
    function save_file($file, $defaultName)
    {
        // Handle File Upload
        if ($file) {

            // Get filename with extension
            $filenameWithExt = $file->getClientOriginalName();

            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);

            // Get just ext
            $extension = $file->getClientOriginalExtension();

            //Filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            // Upload Image
            $path = $file->storeAs('/public/profile_images', $fileNameToStore);
        } else {
            $fileNameToStore = $defaultName;
        }

        return $fileNameToStore;
    }
}

if (!function_exists('get_role')) {
    /**
     * Return user role name.
     * */
    function get_role()
    {
        // Handle File Upload
        $role =  auth()->user()->role->name;
        return $role;
    }
}

if (!function_exists('get_item_details')) {
    /**
     * Return an item latest details.
     * */
    function get_item_details($item, $itemType = 'approved')
    {
        if ($item) {
            switch ($itemType) {
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
        if ($userId == null) {
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
if (!function_exists('getUserBranches')) {

    function getUserBranches($id)
    {
        $branches = Branch::where('user_id', $id)->get();
        return $branches;
    }
}

// This will return items based on status one or multiple status.
if (!function_exists('getUserItemsBasedOnStatus')) {

    function getUserItemsBasedOnStatus($branchIds, $status, $count, $all = false)
    {
        $item = Item::whereHas(
            'itemFullDetails',
            function ($query) use ($status) {
                $query->whereIn('details_status', $status);
            }
        )->whereIn('branch_id', $branchIds);

        if (!$all) {
            $item = $item->where('status', '1');
        }
        if ($count) {
            $item = $item->count();
        } else {
            $item = $item->get();
        }
        return $item;
    }
}

// This function get specific  user branches and returns user menus.
if (!function_exists('loadUserMenuData')) {
    function loadUserMenuData($userId, $statusCheck = false)
    {
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
if (!function_exists('getUserMenus')) {
    function getUserMenus($branchIds, $statusCheck = false)
    {
        $menu = Menu::whereIn('branch_id', $branchIds);

        if ($statusCheck) {
            $menu = $menu->where('status', '1');
        }

        $menu = $menu->latest()->paginate(10);
        return $menu;
    }
}


// This will return menus based on branch ids.
if (!function_exists('getBranchesBasedOnStatus')) {
    function getBranchesBasedOnStatus($status)
    {
        $branches = Branch::whereHas(
            'branchFullDetails',
            function ($query) use ($status) {
                $query->where('status', '=', $status);
            }
        )->latest()->paginate(10);
        return $branches;
    }
}

// This function abort the process for the role we pass.
if (!function_exists('abortUrlFor')) {
    function abortUrlFor($role = null, $userId = null, $branchId = null)
    {
        if ($role != null) {
            if (get_role() == $role) {
                abort(404);
            }
        } elseif ($userId != null && $branchId != null) {
            if ($userId != $branchId) {
                abort(404);
            }
        }
    }
}

// This will return orders based on branch ids of a user.
if (!function_exists('loadUserAllOrders')) {
    function loadUserAllOrders($userId, $status, $perPage = NULL)
    {
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }
        $orders = Order::whereIn('branch_id', $branchIds)->whereIn('status', $status)->with('customer.blockedCustomer');

        if ($perPage) {
            $orders = $orders->latest()->paginate($perPage);
        } else {
            $orders = $orders->latest()->get();
        }

        return $orders;
    }
}

if (!function_exists('get_branch_details')) {
    /**
     * Return a branch latest details.
     * */
    function get_branch_details($branch, $branchType = 'approved')
    {

        if ($branch) {
            switch ($branchType) {
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
if (!function_exists('show_order_itmes')) {
    function show_order_itmes($items, $api = false)
    {

        // Open html warapper for list of items.
        $output = "<span class='order_content_list'><ul>";

        $items = json_decode($items);

        $items = $items->contents;

        $contents = [];
        for ($k = 0; $k < count($items); $k++) {

            // Create the correct format of key.
            $key = 'item_' . ($k + 1);

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

            $price =  $itemRecord->approvedItemDetails->price;

            $output .= "<li>$title, $item->count</li>";

            $contents[] = ['title' => $title, 'count' => $item->count, 'price' => $price];
        }
        // Close html Wrapper.
        $output .= "</ul></span>";

        return ($api) ? $contents : $output;
    }
}

// This will check if item is assigned for menu.
if (!function_exists('select_item_logic')) {

    function select_item_logic($menu_items, $id)
    {

        $item_ids = (array) json_decode($menu_items);

        if (in_array($id, $item_ids)) {
            return 'selected="selected"';
        }
    }
}

// This will return menu items for views.
if (!function_exists('show_menu_itmes')) {
    function show_menu_itmes($items)
    {

        $itemIDs = json_decode($items);
        $items = Item::whereIn('id', $itemIDs)->get();

        $allItems = [];
        foreach ($items as $item) {
            $allItems[] = '<a href="/item/' . $item->id . '">' . $item->approvedItemDetails->title . '</a>';
        }
        $allItems = implode(", ", $allItems);

        return "<p>$allItems</p>";
    }
}


// General function to get orders, based on the provided params.
if (!function_exists('get_orders')) {
    function get_orders($type, $request, $realTime = false, $keyword = null, $code = null)
    {
        // Order lists based on different status. active([Pending, Accept, Processing, Delivery]) and history ([completed, Canceled])
        $status = [];
        $drivers = [];
        $pageTitle = 'Waiting Orders'; // Livewire can't access Request::is in blade so we need to pass the name.
        $perPage = 25;
        switch ($type) {
            case 'history':
                $status = ['completed', 'canceld', 'reject'];
                break;
            case 'active-orders':
                $status = (get_role() == "restaurant") ? ['pending', 'processing', 'delivered'] : ['processing', 'delivered'];
                $pageTitle = 'Active Orders';
                break;
            default:
                $status = [];
        };

        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant") {
            $userId = Auth::user()->id;
            $orders = loadUserAllOrders($userId, $status, $perPage);
            if ($realTime) {
                return view('livewire.restaurant.active-orders', compact('orders', 'drivers'))->extends('dashboards.restaurant.layouts.master');
            }
            return view('dashboards.restaurant.orders.index', compact('orders'));
        }

        // For real time data, datatable search is enough.
        $keyword = ($request) ? $request->get('search') : $keyword; // @TODO: is this line needed as well?
        $keyword = (!$keyword && isset($_GET['search'])) ? $_GET['search'] : $keyword;
        $code = (isset($_GET['code'])) ? $_GET['code'] : $code;

        $order_query = Order::whereIn('status', $status);

        if ($code) {
            $order_query = $order_query->where('id', $code);
        }

        if (!empty($keyword)) {
            $orders = $order_query->wherehas(
                'branchDetails',
                function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%");
                }
            );
        }

        if ($type == 'waiting-orders') {
            $orders = get_waiting_orders($keyword, $perPage, false, $code);
            $drivers = Driver::all();
        } else {

            // Get orders that in addition to other filters, also should have drivers.
            if ($type == 'active-orders') {
                $order_query = $order_query->whereHas('deliveryDetails', function ($subquery) {
                    $subquery->Where('delivery_type', 'own')->orWhereNotNull('driver_id');
                });
            }

            $orders = $order_query->latest()->paginate($perPage);
        }

        // Real time template are in livewire dir.
        if ($realTime) {
            return view('livewire.waiting-orders', compact('orders', 'drivers', 'pageTitle'));
        }

        // Order history routes are using main template.
        return view('order.orders.index', compact('orders', 'drivers'));
    }
}

// Send notification based on user Ids and message we provide.
if (!function_exists('send_notification')) {
    function send_notification(array $notifyUsers, $userId, $message)
    {
        $notifyUsers = User::whereIn('id', $notifyUsers)->get();

        for ($i = 0; $i < sizeof($notifyUsers); $i++) {
            event(new \App\Events\NotificationEvent('Notification', $notifyUsers[$i]));
        }

        Notification::send($notifyUsers, new \App\Notifications\UpdateNotification($message, $userId));
    }
}

// This will return menu items for views.
if (!function_exists('update_order')) {
    function update_order($requestData, $id, $api = false)
    {

        try {
            DB::transaction(function () use ($requestData, $id) {
                // $deliver_update = false;
                // $requestData['has_delivery'] = 0;

                // if ($requestData['delivery_type'] != 'self') {
                //     $requestData['has_delivery'] = 1;
                //     $deliver_update = true;
                // }

                $order = Order::findOrFail($id);

                $total_price = calculate_order_items_price($requestData['contents']);

                $orderData = [
                    'branch_id' => $requestData['branch_id'],
                    'customer_id' => $requestData['customer_id'],
                    // 'has_delivery' => $requestData['has_delivery'],
                    'title' => $requestData['title'],
                    // 'commission_value' => $requestData['commission_value'],
                    'total' => $total_price,
                    'status' => $requestData['status'],
                    'note' => $requestData['note'],
                    'reciever_phone' => $requestData['reciever_phone'],
                    'contents' => $requestData['contents'],
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ];

                calculate_order_commission_value($orderData, $total_price);

                $order->update($orderData);

                $delivery_comission = calculate_order_delivery_commission_value($id);

                $detailsData = [
                    'delivery_type' => $requestData['delivery_type'], 
                    'delivery_commission' => $delivery_comission
                ];

                $result = DeliveryDetails::where('order_id', $id)->update($detailsData);

                // print_r($result);
                // die;
                
            });
        } catch (\Throwable $th) {
            dd($th);
        }

        event(new \App\Events\UpdateEvent('Order Updated!', $id));
        return redirect()->back()->with('flash_message', 'Order updated!');
    }
}

/**
 * Validate request inputs.
 *
 * @param object $request
 */
if (!function_exists('validateOrderInputs')) {
    function validateOrderInputs($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'branch_id' => 'required|integer',
                'delivery_type' => 'required',
                'total' => 'required|integer',
                'commission_value' => 'required',
                'status' => 'required',
                'reciever_phone' => 'required',
                'contents' => 'required',
                'address_id' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
    }
}

// Calculate the percentage between two numbers.
if (!function_exists('calculate_percentage')) {
    function calculate_percentage($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            $oldValue++;
            $newValue++;
        }
        $percentage = (($newValue - $oldValue) / $oldValue) * 100;
        return round($percentage, 2);
    }
}

// Format a percentage number to show on blade.
if (!function_exists('format_percentage')) {
    function format_percentage($percentage)
    {
        if ($percentage >= 1) {
            $percentage = ['+' . $percentage, 'text-success'];
        } else {
            $percentage = [$percentage, 'text-danger'];
        }
        return $percentage;
    }
}

// Format a percentage number to show on blade.
if (!function_exists('translate_term')) {
    function translate_term($status)
    {
        switch ($status) {
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
            case 'main_food':
                return 'غذای اصلی';
                break;
            case 'dessert':
                return 'دسر';
                break;
            default:
                return 'لغو شده';
        }
    }
}

// Format promissed date to a farsi readable date.
if (!function_exists('get_promissed_date')) {
    function get_promissed_date($date)
    {
        if ($date) {
            Carbon::setLocale('fa');
            return Carbon::parse($date)->diffForHumans();
        }

        return '';
    }
}

// Format promissed date to a farsi readable date.
if (!function_exists('get_farsi_date')) {
    function get_farsi_date($date)
    {
        if ($date) {
            Carbon::setLocale('fa');
            return Carbon::parse($date)->format('M-d');
        }

        return '';
    }
}

// Check if an order is late, add a class for css style applying.
if (!function_exists('is_order_late')) {
    function is_order_late($date, $status)
    {
        $completed_status = ['reject', 'canceld', 'completed'];

        return (!in_array($status, $completed_status) && Carbon::parse($date)->isPast()) ? 'order_is_late' : '';
    }
}

// Update table column with boolean values.
if (!function_exists('columnToggleUpdate')) {
    function columnToggleUpdate($table, $column, $record_id)
    {
        DB::statement("UPDATE $table SET $column = 1 - status WHERE id = $record_id");
    }
}

// Update table column with boolean values.
if (!function_exists('get_customer_status')) {
    function get_customer_status($customer_id)
    {

        $blockCustomerStatus = DB::table('block_customers')->where('customer_id', '=', $customer_id)->value('status');

        return ($blockCustomerStatus) ?: 'Notblocked';
    }
}


// On Waiting orders we list and filter all orders that with these 2 conditions:
//  1. Pending status and created time is older that 1 mins. (It means order is created 1 minute a go and still restaruant not responded to it)
//  2. Orders that restaurant requested delivery for them and yet no driver is assigned.
if (!function_exists('get_waiting_orders')) {
    function get_waiting_orders($keyword, $perPage, $count = false, $code = null)
    {
        // Get orders from 10 minutes ago.
        $timeOffSet = Carbon::now()->subMinutes(1)->toDateTimeString();
        $order_query = Order::latest();
        $order_query->where(function ($query) use ($timeOffSet, $keyword, $code) {
            // Get orders that created 2 mins ago and still not responsed by restaurant.
            $query->where('status', 'pending')->where('orders.created_at', '<', $timeOffSet)->whereHas('branchDetails', function ($sub) use ($keyword) {
                $sub->where('title', 'LIKE', "%" . $keyword . "%");
            });
            if ($code) { // if code added, filter by ids
                $query->where('id', 'LIKE', "%" . $code . "%");
            }
        })->orwhere(function ($query) use ($keyword, $code) {
            // Get orders that are assigned to company to delivery and yet have no driver assigned to them.
            $query->whereHas('deliveryDetails', function ($subquery) {
                $subquery->where('delivery_type', 'company')->whereNull('driver_id');
            })->where('status', '<>', 'reject')->whereHas('branchDetails', function ($sub) use ($keyword) {
                $sub->where('title', 'LIKE', "%" . $keyword . "%");
            });
            if ($code) { // if code added, filter by ids
                $query->where('id', 'LIKE', "%" . $code . "%");
            }
        });

        return ($count) ?  $order_query->count() : $order_query->paginate($perPage);
    }
}

// Update table column with boolean values.
if (!function_exists('get_current_branch_id')) {
    function get_current_branch_id()
    {
        $userId = Auth::user()->id;
        $branch = Branch::where('user_id', $userId)->first();
        $branchID = (is_object($branch)) ? $branch->id : NULL;
        return $branchID;
    }
}

// get setting from configurations.
if (!function_exists('setting_config')) {
    function setting_config($key)
    {
        return Setting::where('key', $key)->pluck('value')->toArray();
    }
}

// Get branches that have had orders between the given range of dates.
if (!function_exists('get_active_branches')) {
    function get_active_branches($count = false)
    {

        // Select all orders that paid columns is 0.
        $orders = DB::table('orders')
            ->select(DB::raw('branch_id'))
            ->where('paid', 0)
            ->get()->toArray();

        $active_branches = [];
        foreach ($orders as $order) {
            $active_branches[] = $order->branch_id;
        }

        return ($count) ? Branch::whereIN('id', $active_branches)->count() : Branch::whereIN('id', $active_branches)->get();
    }
}

// Get the branch last payment.
if (!function_exists('get_this_branch_last_paid_date')) {
    function get_this_branch_last_paid_date($branch_id)
    {

        $payment = Payment::where('branch_id', $branch_id)->latest('range_to')->first();

        // Get last paid field.
        $last_paid = (is_object($payment)) ? $payment->range_to : NULL;

        // If the last date paid was not available, then select first day of last month. 
        if (is_null($last_paid)) {
            $last_paid = new Carbon('first day of last month');
        } else {
            $last_paid = Carbon::parse($last_paid);
        }

        return $last_paid;
    }

    // Calculate the total price of items for this order.
    if (!function_exists('calculate_order_items_price')) {
        function calculate_order_items_price($json_items)
        {
            $total_price = 0;
            $items = json_decode($json_items);
            foreach ($items->contents as $key => $item) {
                $item = reset($item); // This will return object contains just count, price, item_id
                $total_price += $item->count * $item->price;
            }
            return $total_price;
        }
    }
    // Calculate the commissions (general commssion and delivery commission.
    if (!function_exists('calculate_order_commission_value')) {
        function calculate_order_commission_value(&$orderData, $total_price)
        {
            $commission_obj = DB::table('commissions')->where('type', 'general')->first();
            if ($commission_obj) {
                $commission_percent = $commission_obj->percentage;
                $commission_value = round($total_price * ($commission_percent / 100));
                $orderData['commission_value'] = $commission_value;
            }
        }
    }

    // Calculate the commissions (general commssion and delivery commission.
    if (!function_exists('calculate_order_delivery_commission_value')) {
        function calculate_order_delivery_commission_value($order_id)
        {
            // Take the total price and calculate if driver is assigned, otherwise return 0;
            $total_price = Order::where('id', $order_id)->whereHas('deliveryDetails', function ($subquery) {
                $subquery->whereNotNull('driver_id');
            })->first()->total ?? 0;

            $commission_obj = DB::table('commissions')->where('type', 'delivery')->first();
            if ($commission_obj) {
                $commission_percent = $commission_obj->percentage;
                $commission_value = round($total_price * ($commission_percent / 100));
                
                return $commission_value;
            }
        }
    }

    // Update all orders paid status after final approval by finance manager.
    if (!function_exists('orders_paid_status')) {
        function orders_paid_status($id, $status)
        {
            DB::table('orders')->where('payment_id', $id)->update(['paid' => $status]);
        }
    }
    if (!function_exists('get_branches_by_status')) {
        function get_branches_by_status($status)
        {
            $branchIDs = DB::table('payments')->where('status', $status)->get()->pluck('branch_id')->toArray();
            return DB::table('users')
                ->whereIn('id', DB::table('branches')->whereIn('id', $branchIDs)->get()->pluck('user_id'))
                ->get()->toArray();
        }
    }

    // @TODO: is this needed.
    if (!function_exists('customer_address_add')) {
        function customer_address_add($customer_id, $data)
        {
            $address = DB::table('customer_addresses')->find($data);
            if ($address) {
                return $address->id;
            }
            return DB::table('customer_addresses')->insertGetId([
                'customer_id' => $customer_id,
                'address' => $data
            ]);
        }
    }

    if (!function_exists('get_active_orders_count')) {
        function get_active_orders_count($branchID)
        {

            $status = (get_role() == "restaurant") ? ['pending', 'processing', 'delivered'] : ['processing', 'delivered'];

            $orders = Order::whereIn('status', $status);

            // Active orders count for restaurant.
            if ($branchID) {
                $orders = $orders->where('branch_id', $branchID);
            } else {
                // Active orders count for Support.
                $orders = $orders->whereHas('deliveryDetails', function ($subquery) {
                    $subquery->Where('delivery_type', 'own')->orWhereNotNull('driver_id');
                });
            }

            return $orders->count();
        }
    }

    if (!function_exists('get_updated_counts_for_JS_update')) {
        function get_updated_counts_for_JS_update()
        {
            $branchID = false; // By default this data is for support not for branch.
            if (get_role() == "restaurant") {
                // Get user branch.
                $branchID = get_current_branch_id();

                // Get active orders count.
                $data['restaurantActiveOrders'] = get_active_orders_count($branchID);
            } else {
                // Get active orders count.
                $data['activeOrders'] = get_active_orders_count(false);

                // Get waiting orders count.
                $data['waitingOrders'] = get_waiting_orders("%", null, true);
            }

            return $data;
        }
    }


    if (!function_exists('company_delivery_and_payments')) {
        function company_delivery_and_payments(&$payments)
        {
            foreach ($payments as $key => &$pay) {
                $companyOrders = 0;
                $companyOrdersTotal = 0;
        
                foreach ($pay->orders as $order) {
                    if ($details = DeliveryDetails::where('order_id', $order->id)->first()) {
                        if ($details->delivery_type == 'company') {
                            $companyOrders++;
                            $companyOrdersTotal += $order->total;
                        }
                    }
                }
                $pay->company_order = $companyOrders;
                $pay->company_order_total = $companyOrdersTotal;
            }
        }
    }
}
