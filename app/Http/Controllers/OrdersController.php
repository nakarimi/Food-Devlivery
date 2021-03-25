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

        // TODO: we will add notificaiton for company as well.
        switch($status) {
            case 'approved' :
                $field = 'approved_time';
                send_notification([$customer_id], $userId, 'Your order has been Approved');
                break;
            case 'reject' :
                $field = 'rejected_time';
                send_notification([$customer_id], $userId, 'Your order has been rejected');
            break;
            case 'processing' :
                $field = 'processing_time';
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

        $order = Order::findOrFail($id);
        $order->status = $status;
        $order->save();

        // Update timing as well.
        $updateDeliveryTimeDetails = [
            $field => Carbon::now()->format('Y-m-d H:i:s'),
        ];
        OrderTimeDetails::where('order_id', $id)->update($updateDeliveryTimeDetails);
        event(new \App\Events\UpdateEvent('Order Updated!'));

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
        DeliveryDetails::where('order_id', $id)->update(['driver_id' => $driver_id]);
        Driver::where('id', $driver_id)->update(['status' => 'busy']);
        event(new \App\Events\UpdateEvent('Order Updated!'));
        send_notification([$driver_id], $userId, 'New Order has been assigned to you');

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
        event(new \App\Events\UpdateEvent('Order Updated!'));
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
            $driver = Driver::findOrFail($driver_id);
            $driver->status = $status;
            $driver->save();
        }
    }
}
