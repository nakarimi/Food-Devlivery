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

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // If it is restaurant then user will have some restricted data.
        if (get_role() == "restaurant"){
            $userId = Auth::user()->id;
            $orders = loadUserAllOrders($userId);
            return view('dashboards.restaurant.orders.index', compact('orders'));
        }

        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $orders = Order::where('title', 'LIKE', "%$keyword%")->latest()->paginate($perPage);
        } else {
            $orders = Order::latest()->paginate($perPage);
        }

        return view('order.orders.index', compact('orders'));
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
        abortUrlFor("restaurant");
        $data = $this->dropdown_data($id);

        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            $data = $this->dropdown_data(false, $userId);
        }



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
        $data = $this->dropdown_data($id);
        if (get_role() == "restaurant"){
            $userId = auth()->user()->id;
            $data = $this->dropdown_data($id, $userId);
        }
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
			'contents' => 'required'
		]);
        $requestData = $request->all();

        $deliver_update = false;

        if ($requestData['delivery_type'] != 'self') {
            $requestData['has_delivery'] = 1;
            $deliver_update = true;
        }

        $order = Order::findOrFail($id);
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


        return redirect('orders')->with('flash_message', 'Order updated!');
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

        return redirect('orders')->with('flash_message', 'Order deleted!');
    }

    /**
     * Load necessary data for dropdowns.
     *
     * @param  int  $id
     *
     * @return array $data
     */
    public function dropdown_data($id = false, $userId = null) {
        abortUrlFor("restaurant");

        // Pass branches for dropdown list form.
        if ($userId != null){
            // If User Id passed it will load branches to that specific user.
            $data['branches'] = getUserBranches($userId);
        }
        else {
            $data['branches'] = Branch::all();
        }

        $data['customers'] = User::where('role_id', 5)->get();


        $data['drivers'] = Driver::all();

        // Pass Item to view. (For Edit form)
        // $item = Item::findOrFail($id);
        $data['order'] = ($id) ? Order::findOrFail($id) : null;
        return $data;
    }

    /**
     * Load necessary data for dropdowns.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function updateOrderStatus(Request $request) {

        $id = $request['order_id'];
        $status = $request['status'];

        $order = Order::findOrFail($id);
        $order->status = $status;
        $order->save();
    }
}
