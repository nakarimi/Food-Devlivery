<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $keyword = $request->get('search');
        $perPage = 10;

        if (!empty($keyword)) {
            $payment = Payment::wherehas(
                'branchDetails',  function ($query) use ($keyword) {
                $query->where('title', 'LIKE', "%$keyword%");
            })->orWhere('paid_amount', 'LIKE', "%$keyword%")
                ->orWhere('date_and_time', 'LIKE', "%$keyword%")
                ->orWhere('note', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        } else {
            $payment = Payment::latest()->paginate($perPage);
        }

        return view('payment.payment.index', compact('payment'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // $data = $this->dropdown_data();
        // foreach($data['branches'] as $branch) {
        //     print_r($branch);
        // }
        // print_r();die;
        return view('payment.payment.create', $data);
    }

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
			'branch_id' => 'required',
			'reciever_id' => 'required',
			'paid_amount' => 'required',
			'date_and_time' => 'required'
		]);
        if (get_role() == "admin" || get_role() == "support"){
            $request->request->add( ['status' => 'approved']);
        }
        $requestData = $request->all();

        Payment::create($requestData);

        return redirect('payment')->with('flash_message', 'Payment added!');
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
        $payment = Payment::findOrFail($id);

        return view('payment.payment.show', compact('payment'));
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  *
    //  * @return \Illuminate\View\View
    //  */
    // public function edit($id)
    // {
    //     // $data = $this->dropdown_data($id);
    //     $data['payment'] = Payment::findOrFail($id);
    //     return view('payment.payment.edit', $data);
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @param  int  $id
    //  *
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    //  */
    // public function update(Request $request, $id)
    // {
    //     $this->validate($request, [
	// 		'branch_id' => 'required',
	// 		'reciever_id' => 'required',
	// 		'paid_amount' => 'required',
	// 		'date_and_time' => 'required'
	// 	]);
    //     $requestData = $request->all();

    //     $payment = Payment::findOrFail($id);
    //     $payment->update($requestData);

    //     return redirect('payment')->with('flash_message', 'Payment updated!');
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  *
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    //  */
    // public function destroy($id)
    // {
    //     Payment::destroy($id);

    //     return redirect('payment')->with('flash_message', 'Payment deleted!');
    // }

    // public function dropdown_data($id = false) {

    //     // Pass Branches for dropdown list form.
    //     $data['branches'] = DB::table("branches")->select('branche_main_info.title', 'branches.id')->where('branche_main_info.status', 'approved')->join('branche_main_info', 'branche_main_info.business_id', '=', 'branches.id')->latest('branche_main_info.created_at')->get();
    //     // Pass Users for dropdown list form.
    //     $data['users'] = User::all();

    //     return $data;
    // }

    /**
     * Active Payment.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activePayments(Request $request)
    {
        $payments = [];
        $branchID = get_current_branch_info()->id;
        $payment_range_in_days = setting_config('payment_range_in_days')[0];

        $lastPayment = new Carbon('first day of last month');
        $today = Carbon::now()->format('Y-m-d');

        while($lastPayment->lt($today)) {     
            $from = $lastPayment->format('Y-m-d');                        
            $to = $lastPayment->addDays($payment_range_in_days)->format('Y-m-d');
            $payments[] = $this->calculate_orders_commission($from, $to, $branchID);
        }
        
        return view('dashboards.restaurant.payment.index', compact('payments'));
    }

    /**
     * This helper get the params, and calculate orders based on those params.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function calculate_orders_commission($from, $to, $branchID)
    {

        // Get all orders.
        $orders = Order::where('branch_id', $branchID)->whereBetween('created_at', [$from, $to])->get();

        $totalOrders = $totalOrdersPrice = $totalGeneralCommission = $totalDeliveryCommission = 0;

        $payment = new \stdClass;

        foreach ($orders as $order) {
            $totalOrders ++;
            $totalOrdersPrice += $order->total;
            $totalGeneralCommission += $order->commission_value;
            $totalDeliveryCommission += (is_object($order->deliveryDetails)) ? $order->deliveryDetails->delivery_commission : 0;
        }

        $payment->range_from = Carbon::parse($from)->format('M-d');
        $payment->range_to = Carbon::parse($to)->format('M-d');
        $payment->totalOrders = $totalOrders;
        $payment->totalOrdersPrice = $totalOrdersPrice;
        $payment->totalGeneralCommission = $totalGeneralCommission;
        $payment->totalDeliveryCommission = $totalDeliveryCommission;

        return $payment;
        
    }

    /**
     * Payment history.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paymentHistory(Request $request)
    {
        $perPage = 10;
        $branchID = get_current_branch_info();
        $payment = Payment::where('branch_id', $branchID->id)->latest()->paginate($perPage);

        return view('dashboards.restaurant.payment.index', compact('payment'));
    }

    // public function restaurantPaymentsCreate()
    // {
    //     if (get_role() == "restaurant"){
    //         return view('dashboards.restaurant.payment.create');
    //     }
    // }

    // public function SaveRestaurantPayments(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $branchID = Branch::where('user_id', $userId)->first()->id;
    //     $this->validate($request, [
    //         'paid_amount' => 'required',
    //         'date_and_time' => 'required'
    //     ]);
    //     $request->request->add( ['branch_id' => $branchID, 'reciever_id' => 1]);
    //     $requestData = $request->all();
    //     Payment::create($requestData );
    //     return redirect('paymentHistory')->with('flash_message', 'Payment Added!');
    // }

    public function rejectPayment(Request $request)
    {
        $paymentId = $request->payment_id;
        $this->changePaymentStatus($paymentId,'rejected');
        return redirect()->back()->with('flash_message', 'Payment Rejected!');

    }

    public function approvePayment(Request $request)
    {
        $paymentId = $request->payment_id;
       $this->changePaymentStatus($paymentId,'approved');
        return redirect()->back()->with('flash_message', 'Payment Approved!');

    }

    public function changePaymentStatus($paymentId, $status)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->status = $status;
        $payment->save();
    }

}
