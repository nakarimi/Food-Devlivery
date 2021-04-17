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
     * Pending Payment.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function pendingPayments(Request $request)
    {

        // Get payment range to divide orders payment based on this.
        $payment_range_in_days = setting_config('payment_range_in_days')[0];

        // Pass active branches for Dropdown select.
        $activeBranches = get_active_branches();
        
        // General placeholder for all payments
        $payments = [];

        if ($request['branch_id']) {

            // The last date that a restaurant paid. @Todo, this shoudl change to actual month.
            $lastPayment = get_this_branch_last_paid_date($request['branch_id']);

             // The order payment shoudl calculate till the other day.
            $ToDate = Carbon::now();

            // Loop until reach the specified date.
            while($lastPayment->lt($ToDate)) {
                $from = $lastPayment->format('Y-m-d');                        
                $to = $lastPayment->addDays($payment_range_in_days);
                
                // Since we are adding range to the "to" variable, sometime it go beyond the yesterday, it comes to today and may go to future. we need to controll this.
                if ($to->gt($ToDate)) {
                   $to = $ToDate;
                }

                // Get order calculation.
                $payments[] = $this->calculate_orders_commission($from, $to->format('Y-m-d'), $request['branch_id']);
            }
        }
        
        // Remove empty indexes.
        $payments = array_filter($payments);
        
        return view('dashboards.finance_officer.payment.index', compact('payments', 'activeBranches'));
    }

    /**
     * Active Payment.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activePayments(Request $request)
    {
        $perPage = 10;
        $branchPayments = NULL;
        $branchID = isset($request->branch_id) ? $request->branch_id : get_current_branch_id();

        if(!is_null($branchID)) {
            $branchPayments = Payment::where('branch_id', $branchID)->where('status', 'activated')->latest()->paginate($perPage);
            
            // If current user is restaurant then laod this view.
            if (get_current_branch_id()) {
                $payments =  $branchPayments;
                return view('dashboards.restaurant.payment.index', compact('payments'));
            }
        }

        // For finance officer if payment was already filtered by branch, so not load all.
        $payments = Payment::where('status', 'activated')->latest()->paginate($perPage);
        
        $active_branches = [];
        foreach($payments as $payment) {
            $active_branches[] = $payment->branch_id;
        }

        $activeBranches = Branch::whereIN('id', $active_branches)->get();

        $payments = (isset($request->branch_id)) ? $branchPayments : $payments;
        return view('dashboards.finance_officer.payment.index', compact('payments', 'activeBranches'));
    }
    
    /**
     * This helper get the params, and calculate orders based on those params.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function calculate_orders_commission($from, $to, $branchID)
    {

        // Get all orders.
        $orders = Order::whereBetween('created_at', [$from, $to])->where('branch_id', $branchID)->get();

        // Initialize variable for later assignment.
        $totalOrders = $totalOrdersPrice = $totalGeneralCommission = $totalDeliveryCommission = 0;

        // Base payment object to collect all calculations and pass to view.
        $payment = new \stdClass;

        // Go through every branch orders and sum up the needed values.
        foreach ($orders as $order) {
            $totalOrders ++;
            $totalOrdersPrice += $order->total;
            $totalGeneralCommission += $order->commission_value;
            $totalDeliveryCommission += (is_object($order->deliveryDetails)) ? $order->deliveryDetails->delivery_commission : 0;
        }

        // Assing a new row to payment object.
        $payment->from = Carbon::parse($from)->format('Y-m-d');
        $payment->to = Carbon::parse($to)->format('Y-m-d');
        $payment->range_from = Carbon::parse($from)->format('M-d');
        $payment->range_to = Carbon::parse($to)->subDays(1)->format('M-d');
        $payment->total_order = $totalOrders;
        $payment->branchTitle = Branch::findOrFail($branchID)->branchDetails->title;
        $payment->total_order_income = $totalOrdersPrice;
        $payment->total_general_commission = $totalGeneralCommission;
        $payment->total_delivery_commission = $totalDeliveryCommission;

        // Return orders if not empty.
        return ($totalOrders > 0) ? $payment : [];
        
    }

    /**
     * Payment history.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paymentHistory(Request $request)
    {
        $perPage = 10;
        $branchID = get_current_branch_id();
        
        if(!is_null($branchID)) {
            $payments = Payment::where('branch_id', $branchID)->latest()->paginate($perPage);
            return view('dashboards.restaurant.payment.index', compact('payments'));  
        }

        $payments = Payment::latest()->paginate($perPage);
        // return view('payment.payment.index', compact('payments'));
        return view('dashboards.finance_officer.payment.index', compact('payments'));
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

    // public function rejectPayment(Request $request)
    // {
    //     $branch_id = $request->branch_id;
    //     $reciever_id = $request->reciever_id;
    //     $total_order = $request->total_order;
    //     $total_general_commission = $request->total_general_commission;
    //     $total_delivery_commission = $request->total_delivery_commission;
    //     $start_date = $request->start_date;
    //     $end_date = $request->end_date;
    //     $status = ;
        

    //     $this->changePaymentStatus($paymentId,'rejected');
    //     return redirect()->back()->with('flash_message', 'Payment Rejected!');

    // }

    public function activate_payment(Request $request)
    {
        
        $data['branch_id'] = $request->branch_id;
        $data['reciever_id'] = $request->reciever_id;
        $data['total_order'] = $request->total_order;
        $data['total_order_income'] = $request->total_order_income;
        $data['total_general_commission'] = $request->total_general_commission;
        $data['total_delivery_commission'] = $request->total_delivery_commission;
        $data['range_from'] = $request->range_from;
        $data['range_to'] = $request->range_to;
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $data['status'] = 'activated';

        $inserted = DB::table('payments')->insertOrIgnore($data);

        if ($inserted) {
            return redirect()->back()->with('flash_message', 'Payment Activated!');
        }
        else {
            return redirect()->back()->with('flash_message', 'Payment Activation Failed!');
        }
        

    }

    
    public function recievePayment(Request $request)
    {
        $paymentId = $request->payment_id;
        $this->changePaymentStatus($paymentId,'recieved');
        return redirect()->back()->with('flash_message', 'Payment Recieved!');

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
