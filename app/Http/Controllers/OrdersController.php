<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use App\Models\Driver;
use App\Models\DeliveryDetails;
use App\Models\OrderTimeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\OrderTracking;
use App\Models\Item;

class OrdersController extends Controller
{

    /**
     * Display a listing of old orders.
     *
     * @return \Illuminate\View\View
     */
    public function orderHistory(Request $request) {
        return get_orders('history', $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            $data = $this->dropdown_data($id, $userId);
            return view('dashboards.restaurant.orders.show', $data);
        }
        $data = $this->dropdown_data($id);

        return view('order.orders.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        abortUrlFor("restaurant");
        $data = $this->dropdown_data($id);
        $data['restaurant_items'] = Item::where('branch_id', $data['order']->branch_id)->with('approvedItemDetails')->get();
        return view('order.orders.edit', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        validateOrderInputs($request);
        $requestData = $request->all();
        update_order($requestData, $id);
        return redirect()->back()->with('flash_message', 'Order updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Order::destroy($id);

        return redirect()->back()->with('flash_message', 'Order deleted!');
    }

    /**
     * Load necessary data for dropdowns.
     *
     * @param  int  $id
     *
     * @return array $data
     */
    public function dropdown_data($id = false, $userId = null) {

        // Pass branches for dropdown list form.
        if ($userId != null){
            // If User Id passed it will load branches to that specific user.
            $data['branches'] = getUserBranches($userId);
        }
        else {
            $data['branches'] = Branch::all();
        }

        $data['customers'] = User::where('role_id', 5)->get();

        // Free drivers.
        $data['drivers'] = Driver::all();

        // Pass Order to view. (For Edit form)
        $data['order'] = ($id) ? Order::findOrFail($id) : null;

        // Prevent other roles from url restriction.
        // the branch user id should equal current user id.
        if (get_role() == "restaurant" && $id != false){
            $branch = Branch::findOrFail($data['order']->branch_id);
            abortUrlFor(null, $userId, $branch->user_id);
        }
        return $data;
    }

    /**
     * Update status of order via ajax call.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function updateOrderStatus(Request $request) {

        $id = $request['order_id'];
        $status = $request['status'];
        $customer_id = $request['customer_id'];
        $userId = \auth()->user()->id;
        $field = '';
        $promissed_time = NULL;
        $message = NULL;
        $now = Carbon::now()->format('Y-m-d H:i:s');

        // TODO: we will add notificaiton for company as well.
        switch($status) {
            case 'processing' :
                $field = 'processing_time';
                $promissed_time = $request['promissed_time'];
                send_notification([$customer_id], $userId, 'Your order has been Approved');
                break;
            case 'reject' :
                $field = 'rejected_time';
                $message = $request['promissed_time']; // promissed_time variable carries message here.
                send_notification([$customer_id], $userId, 'Your order has been rejected');
            break;
            case 'delivered' :
                $field = 'delivery_time';
            break;
            case 'completed' :
                $field = 'completed_time';
                $this->update_driver_status($id, 'free'); 
            break;
            default:
                $field = 'caceled_time';
        }

        try {

            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->status = $status;
            $order->save();

            // Update timing as well.
            $updateDeliveryTimeDetails = [];

            if (!is_null($promissed_time)) {
                $date = Carbon::now()->format('Y-m-d');
                $promissed_time = Carbon::createFromFormat("Y-m-d H:i:s", $date .' '. $promissed_time.':00');

                $updateDeliveryTimeDetails = [
                    $field => $now,
                    'promissed_time' => $promissed_time
                ];
            }

            if (!is_null($message)) {
                $updateDeliveryTimeDetails = [
                    $field => $now,
                    'reject_reason' => $message
                ];
            }

            OrderTimeDetails::updateOrCreate(['order_id' => $id], $updateDeliveryTimeDetails);
            event(new \App\Events\UpdateEvent('Order Updated!', $id));

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return 'Order Not updated!';
        }

    }

    /**
     * Assign driver to order.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function assignDriver(Request $request) {

        $id = $request['order_id'];
        $driver_id = $request['driver_id'];
        $customer_id = $request['customer_id'];
        $userId = \auth()->user()->id;
        
        $detailsData = [
            'driver_id' => $driver_id, 
            'delivery_commission' => calculate_order_delivery_commission_value($id)
        ];

        DeliveryDetails::where('order_id', $id)->update($detailsData);
        $this->update_driver_status($id, 'busy');
        event(new \App\Events\UpdateEvent('Driver assigned!', $id));
        // send_notification([$driver_id], $userId, 'New Order has been assigned to you');

    }

    /**
     * Request Delivery for order.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function requestDelivery(Request $request) {

        $id = $request['order_id'];
        DeliveryDetails::where('order_id', $id)->update(['delivery_type' => 'company']);
        event(new \App\Events\UpdateEvent('Delivery requested!', $id));
    }

    /**
     * Support followup the order.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function followupOrder(Request $request) {
        if ($request['cancel_id'] > 0) {
            OrderTracking::destroy($request['cancel_id']);
        }
        else {
            $requestData = $request->all();
            OrderTracking::create($requestData);
        }
        
        event(new \App\Events\UpdateEvent('Order Updated!', $request['order_id']));
    }

    /**
     * Update driver status.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function update_driver_status($order_id, $status) {
        // Get driver id from order.
        $order = Order::findOrFail($order_id);
        if ($order->has_delivery != 0 && isset($order->deliveryDetails->driver)) {
            $driver_id = $order->deliveryDetails->driver->id;
            // Update status of driver.
            // @TODO, here we need to check if driver has any other order which is not completed yet, since every driver is handling multi order, so only by completing one order it does not mean he is free.
            $driver = Driver::findOrFail($driver_id);
            $driver->status = $status;
            $driver->save();
        }
    }
}
