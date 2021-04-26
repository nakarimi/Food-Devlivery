<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class DriverRequests extends Controller
{
    public function check()
    {
       return "Yes this is driver api section.";
    }

    // List of new orders.
    public function new_orders_list() {

    	$orders = [];
        $ordersList = Order::whereIn('status', ['processing'])->whereHas('deliveryDetails', function ($subquery) {
                $subquery->where('delivery_type', 'company')->whereNull('driver_id');
            })->latest()->get();

        foreach ($ordersList as $order) {
        	$data = []; 
        	$data['id'] = $order->id;
        	$data['price'] = $order->total;
        	$data['reciever_phone'] = $order->reciever_phone;
        	$data['delivery_adress'] = $order->deliveryDetails->delivery_adress;
        	$data['restaurant_title'] = $order->branchDetails->title;
        	$data['restaurant_location'] = $order->branchDetails->location;
        	$data['promissed_time'] = $order->timeDetails->promissed_time;
        	$data['contents'] = show_order_itmes($order->contents, true);

        	$orders[] = $data;
        }

         // "contents": "{\"contents\": [{\"item_1\": {\"count\": \"2\", \"price\": \"100\", \"item_id\": \"9\"}}, {\"item_2\": {\"count\": \"4\", \"price\": \"100\", \"item_id\": \"3\"}}, {\"item_3\": {\"count\": \"8\", \"price\": \"200\", \"item_id\": \"4\"}}]}",

        return $orders;
    }
}
