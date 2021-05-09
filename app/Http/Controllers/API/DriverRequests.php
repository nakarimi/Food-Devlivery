<?php

namespace App\Http\Controllers\API;

use JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\DeliveryDetails;
use App\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Models\OrderTimeDetails;
use Carbon\Carbon;

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
        $driver_id = Driver::where('user_id', JWTAuth::user()->id)->first()->id;
        
        $detailsData = [
            'driver_id' => $driver_id, 
            'delivery_commission' => calculate_order_delivery_commission_value($id)
        ];

        $result = DeliveryDetails::where('order_id', $id)->whereNull('driver_id')->update($detailsData);

        app('App\Http\Controllers\OrdersController')->update_driver_status($id, 'busy');

        event(new \App\Events\UpdateEvent('Order Updated!', $id));
        // send_notification([$driver_id], $userId, 'New Order has been assigned to you');

        // Maybe two drivers pick same order at same time.
        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Order picked by you.!',
            ]);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry a problem happened!',
            ]);
        }
        
    }

    public function delivered_order(Request $request) {
        $id = $request['order_id'];
        $driver_id = Driver::where('user_id', JWTAuth::user()->id)->first()->id;

        try {

            DB::transaction(function () use ($id, $driver_id) {
                
                Order::where('id', $id)->whereHas('deliveryDetails', function ($subquery) use ($driver_id) {
                    $subquery->where('driver_id', $driver_id);
                })->update(['status' => 'delivered']);

                OrderTimeDetails::updateOrCreate(['order_id' => $id], ['delivery_time' => Carbon::now()->format('Y-m-d H:i:s')]);
            });

            // status of driver can't be cahnge dfor one order.
            // app('App\Http\Controllers\OrdersController')->update_driver_status($id, 'free');

            event(new \App\Events\UpdateEvent('Order Updated!', $id));
            // send_notification([$driver_id], $userId, 'New Order has been assigned to you');

            return response()->json([
                'success' => true,
                'message' => 'Order delivered by you!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry a problem happened!',
            ]);
        }

    }

    public function my_orders(Request $request) {
        $driver_id = Driver::where('user_id', JWTAuth::user()->id)->first()->id;

        if ($request->history) {
            return $this->orders_list($newOrders = false, $driver_id, $oldOrders = true);
        }

        return $this->orders_list($newOrders = false, $driver_id);
    }

    public function orders_list($newOrders = true, $driverId = false, $oldOrders = false) {
        $orders = [];

        $ordersList = Order::whereIn('status', ['processing']);

        if ($oldOrders) {
            $ordersList = Order::whereIn('status', ['delivered', 'completed'])->whereDate('created_at', '>', Carbon::now()->subDays(7));
        }

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
            $data['reciever_phone'] = $order->customer->reciever_phone;
            $data['customer_name'] = $order->customer->name;
            $data['delivery_address'] = $order->deliveryDetails->delivery_address;
            $data['restaurant_title'] = $order->branchDetails->title;
            $data['restaurant_logo'] = $order->branchDetails->logo;
            $data['restaurant_mobile'] = $order->branchDetails->contact;
            $data['restaurant_location'] = $order->branchDetails->location;
            $data['promissed_time'] = get_promissed_date($order->timeDetails->promissed_time);
            $data['paid'] = DB::table('received_driver_payments')->where('orders_id', $order->id)->exists();
            $data['contents'] = show_order_itmes($order->contents, true);
        
            $orders[] = $data;
        }

        return $orders;
    }
}
