<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\DeliveryDetails;
use App\Models\Driver;
use JWTAuth;

class DriverRequests extends Controller
{
    // public function check()
    // {
    //    return "Yes this is driver api section.";
    // }

    // List of new orders.
    public function new_orders_list() {
    	return $this->orders_list();
    }

    public function pick_order(Request $request) {
        $id = $request['order_id'];
        $driver_id = $request['driver_id'];
        
        // $customer_id = $request['customer_id'];
        // $userId = \auth()->user()->id;
        DeliveryDetails::where('order_id', $id)->update(['driver_id' => $driver_id]);
        app('App\Http\Controllers\OrdersController')->update_driver_status($id, 'busy');

        event(new \App\Events\UpdateEvent('Order Updated!', $id));
        // send_notification([$driver_id], $userId, 'New Order has been assigned to you');

        return 1;
    }

    public function my_orders(Request $request) {
        $driver_id = Driver::where('user_id', JWTAuth::user()->id)->first()->id;
        return $this->orders_list($newOrders = false, $driver_id);
    }

    public function orders_list($newOrders = true, $driverId = false) {
        $orders = [];
        $ordersList = Order::whereIn('status', ['processing']);

        if ($newOrders) {
            // Get all the orders waiting for delviery.
            $ordersList = $ordersList->whereHas('deliveryDetails', function ($subquery) {
                $subquery->where('delivery_type', 'company')->whereNull('driver_id');
            });
        }
        else if ($driverId) {
            // Get only  the assigned orders list.
            $ordersList = $ordersList->whereHas('deliveryDetails', function ($subquery) use ($driverId) {
                $subquery->where('driver_id', $driverId);
            });
        }

        $ordersList = $ordersList->latest()->get();

        foreach ($ordersList as $order) {
            $data = []; 
            $data['id'] = $order->id;
            $data['price'] = $order->total;
            $data['reciever_phone'] = $order->reciever_phone;
            $data['delivery_address'] = $order->deliveryDetails->delivery_address;
            $data['restaurant_title'] = $order->branchDetails->title;
            $data['restaurant_logo'] = $order->branchDetails->logo;
            $data['restaurant_mobile'] = $order->branchDetails->contact;
            $data['restaurant_location'] = $order->branchDetails->location;
            $data['promissed_time'] = $order->timeDetails->promissed_time;
            $data['contents'] = show_order_itmes($order->contents, true);

            $orders[] = $data;
        }

        return $orders;
    }
}
