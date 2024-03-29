<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Restaurant;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\DeliveryDetails;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{

    /**
     * Pending Payment, this list all payment portion for finance officer to activate them for restaurants.
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
            while ($lastPayment->lt($ToDate)) {
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
        // dd($payments);
        return view('dashboards.finance_officer.payment.index', compact('payments', 'activeBranches'));
    }

    /**
     * Active Payment, load all payment wiht status of activated and paid.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function activePayments(Request $request)
    {
        return $this->get_payments($request, 'IN', ['paid', 'activated']);
    }

    /**
     * Finance officer activate payment, to allow restaurnats pay. Technically we store payment with default status of activated in the table.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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

        $inserted = DB::table('payments')->insertGetId($data);
        // Add payment id to its orders
        DB::table('orders')->whereIn('id', json_decode($request->orders))->update([
            'payment_id' => $inserted,
        ]);

        // Send Notification to restaurant.
        $notifyUser = Branch::find($request->branch_id)->user_id;
        send_notification([$notifyUser], $request->reciever_id, 'پرداخت فعال شد');

        if ($inserted) {
            return redirect()->back()->with('flash_message', 'Payment Activated!');
        } else {
            return redirect()->back()->with('flash_message', 'Payment Activation Failed!');
        }
    }

    /**
     * Restaurant pay the dedicated money. and payment is marked as "paid"
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function pay(Request $request)
    {
        $this->changePaymentStatus($request->payment_id, 'paid');

        // Send Notification to restaurant.
        $payment = Payment::find($request->payment_id);
        $notifyUserFrom = Branch::find($payment->branch_id)->user_id;
        send_notification([$payment->reciever_id], $notifyUserFrom, 'Restaurant paid the payment');

        return redirect()->back()->with('flash_message', 'پرداخت انجام شد، و برای تائید فرستاده شد.');
    }

    /**
     * Finance officer confirm the payment by restaurant.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function recievePayment(Request $request)
    {
        $paymentId = $request->payment_id;
        $this->changePaymentStatus($paymentId, 'done');

        // Send Notification to restaurant.
        $payment = Payment::find($paymentId);
        $notifyUser = Branch::find($payment->branch_id)->user_id;
        send_notification([$notifyUser], $payment->reciever_id, 'پرداخت تائید شد');

        return redirect()->back()->with('flash_message', 'Payment Received!');
    }

    /**
     * Payment history, laod all payments with done status.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function paymentHistory(Request $request)
    {
        return $this->get_payments($request, 'IN', ['approved', 'done']);
    }

    public function changePaymentStatus($paymentId, $status)
    {
        $payment = Payment::findOrFail($paymentId);
        $payment->status = $status;
        $payment->save();
    }

    /**
     * This helper get the params, and calculate orders based on those params.
     * @param  obj  $from
     *  Start date.
     * @param  obj  $to
     *  End date.
     * @param  int  $branchID
     *  Restaurant ID.
     * 
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function calculate_orders_commission($from, $to, $branchID)
    {

        // Get all orders.
        $orders = Order::where('status', 'completed')->whereBetween('created_at', [$from, $to])->where('branch_id', $branchID)->get();

        // Initialize variable for later assignment.
        $totalOrders = $totalOrdersPrice = $total_general_commission = $total_delivery_commission = 0;

        // Base payment object to collect all calculations and pass to view.
        $payment = new \stdClass;
        
        $company_order = 0;
        $company_order_total = 0;
        $company_total_commission = 0;
        $own_delivery = 0;
        $own_delivery_total = 0;
        $own_total_commission = 0;
        // Go through every branch orders and sum up the needed values.
        foreach ($orders as $order) {
            $totalOrders++;
            if ($details = DeliveryDetails::where('order_id', $order->id)->first()) {
                if ($details->delivery_type == 'company') {
                    $company_order++;
                    $company_order_total += $order->total;
                    $company_total_commission += $order->commission_value;
                }else{                            
                    $own_delivery++;
                    $own_delivery_total += $order->total;
                    $own_total_commission += $order->commission_value;
                }
            }

            $totalOrdersPrice += $order->total;
            $total_general_commission += $order->commission_value;
            $total_delivery_commission += (is_object($order->deliveryDetails)) ? $order->deliveryDetails->delivery_commission : 0;
        }
        $payalbe_by_company = $company_order_total - $company_total_commission;
        $payalbe_by_restaurant = $own_delivery_total - $own_total_commission;

        // Assing a new row to payment object.
        $payment->from = Carbon::parse($from)->format('Y-m-d');
        $payment->to = Carbon::parse($to)->format('Y-m-d');
        $payment->range_from = Carbon::parse($from)->format('M-d');
        $payment->range_to = Carbon::parse($to)->subDays(1)->format('M-d');
        $payment->branchTitle = Branch::findOrFail($branchID)->branchDetails->title;
        $payment->total_order_income = $totalOrdersPrice;
        $payment->total_general_commission = $total_general_commission;
        $payment->total_delivery_commission = $total_delivery_commission;
        $payment->payable = calc_payable($payment);
        $payment->orders = $orders->pluck('id');
        
        $payment->total_order = $totalOrders;
        $payment->company_order = $company_order;
        $payment->company_order_total = $company_order_total;
        $payment->own_delivery_total = $own_delivery_total;
        $payment->payalbe_by_company = $payalbe_by_company;
        $payment->payalbe_by_restaurant = $payalbe_by_restaurant;

        // Return orders if not empty.
        return ($totalOrders > 0) ? $payment : [];
    }

    /**
     * This helper get the payments based on the params given.
     *
     * @param  \Illuminate\Http\Request  $request
     *  This carries branch_id, if any exist.
     * @param  string  $condition
     *  This is simple a = or != sign.
     * @param  string  $status
     *  This is status of payment, like paid, activated or so.
     */
    public function get_payments($request, $condition, $status, $view = null, $viewData = null)
    {

        $perPage = 10;
        $branchPayments = NULL;
        $branchID = isset($request->branch_id) ? $request->branch_id : get_current_branch_id();

        // For finance officer if payment was already filtered by branch, so not load all.
        if ($condition == 'IN') {
            $payment_query = Payment::with('orders')->whereIn('status', $status);
        } else {
            $payment_query = Payment::with('orders')->where('status', $condition, $status);
        }

        if (!is_null($branchID)) {
            // If current user is restaurant then laod this view.
            if (get_current_branch_id()) {
                // Since we need correct order, so we need to load them separatly.
                $payments = $payment_query->where('branch_id', $branchID)->paginate($perPage);
                company_delivery_and_payments($payments);

                return view('dashboards.restaurant.payment.index', compact('payments'));
            }
            $branchPayments = $payment_query->where('branch_id', $branchID)->latest()->paginate($perPage);
        }

        // Apply filter on the query of payment if filter data exist
        if (isset($viewData) && $viewData['filter']) {
            if (isset($viewData['filter']['restaurant_id'])) {
                $payment_query->where('branch_id', DB::table('branches')->where('user_id', $viewData['filter']['restaurant_id'])->first()->id);
            }
            if (isset($viewData['filter']['date'])) {
                $payment_query->whereBetween('created_at', $viewData['filter']['date']);
            }
        }
        $payments = $payment_query->latest()->paginate($perPage);
        company_delivery_and_payments($payments);
        // Collect all branches that are considered active.
        $active_branches = [];
        foreach ($payments as $payment) {
            $active_branches[] = $payment->branch_id;
        }

        // This is for filter dropdown. @TODO we need to create a function for this.
        $activeBranches = Branch::whereIN('id', $active_branches)->get();

        // Pass the main payment records, if filtered, so return the filtered branch.
        $payments = (isset($request->branch_id)) ? $branchPayments : $payments;

        // Use different view for different users.
        if ($view) {
            // return $viewData['restaurants'];
            return view($view, compact('payments', 'activeBranches', 'viewData'));
        }
        return view('dashboards.finance_officer.payment.index', compact('payments', 'activeBranches'));
    }

    public function restaurantPendingPayments(Request $request)
    {
        $view = 'dashboards.finance_manager.payment.restaurant.pending_payments';
        $viewData = $this->resturantFilter($request, 'done');

        $viewData['history'] = false;
        return $this->get_payments($request, '=', 'done', $view, $viewData);
    }

    /**
     * Finance officer confirm the payment by restaurant.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function finalApprovePayment(Request $request)
    {
        $paymentId = $request->payment_id;
        $this->changePaymentStatus($paymentId, 'approved');
        orders_paid_status($paymentId, 1);

        // Send Notification to restaurant.
        $payment = Payment::find($paymentId);
        $notifyUser = $payment->reciever_id;
        send_notification([$notifyUser], $payment->reciever_id, 'پرداخت تائید شد');

        return redirect()->back()->with('flash_message', 'Payment Approved!');
    }
    public function restaurantsPaymentHistory(Request $request)
    {
        $view = 'dashboards.finance_manager.payment.restaurant.pending_payments';
        $viewData = $this->resturantFilter($request, 'approved');
        $viewData['history'] = true;
        return $this->get_payments($request, '=', 'approved', $view, $viewData);
    }

    public function resturantFilter($request, $status)
    {
       
        $viewData['restaurants'] = get_branches_by_status($status);
        $viewData['filter'] = ['restaurant_id' => $request->restaurant_id];
        if ($request->get('date-range') && $request->get('date-range') != 'Choose Date') {
            $dates = explode(" - ", $request->get('date-range'));
            $dates[0] = date('Y-m-d', strtotime($dates[0]));
            $dates[1] = date('Y-m-d', strtotime($dates[1]));
            $viewData['filter']['date'] = $dates;
        }
        return $viewData;
    }
}
