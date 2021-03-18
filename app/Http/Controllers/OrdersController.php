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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
//    public function index(Request $request)
//    {
//        return get_orders('active-orders', $request);
//    }

    /**
     * Display a listing of old orders.
     *
     * @return \Illuminate\View\View
     */
    public function orderHistory(Request $request) {
        return get_orders('history', $request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    // public function create()
    // {
    //     return view('order.orders.create');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'title' => 'required',
			'branch_id' => 'required',
			'customer_id' => 'required',
			'has_delivery' => 'required',
			'total' => 'required',
			'commission_value' => 'required',
			'status' => 'required',
			'reciever_phone' => 'required',
			'contents' => 'required'
		]);
        $requestData = $request->all();

        Order::create($requestData);
        event(new \App\Events\UpdateEvent('Order Updated!'));
        return redirect('orders')->with('flash_message', 'Order added!');
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
        $this->validate($request, [
			'title' => 'required',
			'branch_id' => 'required',
			'customer_id' => 'required',
			'status' => 'required',
			'reciever_phone' => 'required',
		]);
        $requestData = $request->all();

        $deliver_update = false;

        if ($requestData['delivery_type'] != 'self') {
            $requestData['has_delivery'] = 1;
            $deliver_update = true;
        }

        $order = Order::findOrFail($id);

        try {

            DB::beginTransaction();

            $order->update($requestData);

            if ($deliver_update) {
                $updateDeliveryDetails = [
                    'delivery_type' => $requestData['delivery_type'],
                    'delivery_adress' => $requestData['delivery_adress'],
                    'driver_id' => $requestData['driver_id'],
                ];

                // Update delivery details. First check if there is a record for it, if not then insert a new record.
                $record = DeliveryDetails::where('order_id', $id)->count();
                if ($record) {
                    DeliveryDetails::where('order_id', $id)->update($updateDeliveryDetails);
                }
                else {
                    $updateDeliveryDetails['order_id'] = $id;
                    DB::table('order_delivery')->insertGetId($updateDeliveryDetails);
                }

            }
            DB::commit();
            event(new \App\Events\UpdateEvent('Order Updated!'));
            return redirect('/activeOrders')->with('flash_message', 'Order updated!');


        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('flash_message', 'Sorry,  update faced a problem!');
        }
        
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
        $field = '';
        switch($status) {
            case 'approved' :
                $field = 'approved_time';
            break;
            case 'reject' :
                $field = 'rejected_time';
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
        // event(new \App\Events\UpdateEvent('Order Updated!'));

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
        DeliveryDetails::where('order_id', $id)->update(['driver_id' => $driver_id]);
        Driver::where('id', $driver_id)->update(['status' => 'busy']);
        event(new \App\Events\UpdateEvent('Order Updated!'));
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
        if ($order->has_delivery != 0) {
            $driver_id = $order->deliveryDetails->driver->id;
            // Update status of driver.
            $driver = Driver::findOrFail($driver_id);
            $driver->status = $status;
            $driver->save();
        }
    }
}
