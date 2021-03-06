<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;

class DashboardsController extends Controller
{
    public function adminDashboard()
    {
        $data = $this->adminDashboardData();
        return view('admin.dashboard', compact('data'));
    }

    public function restaurantDashboard()
    {
        $userId = Auth::user()->id;
        $todayOrders = $this->getOrderDetails($userId, true, 'Today');
        $lastSevenDaysOrders = $this->getOrderDetails($userId, true, 'Last 7 Days');
        $thisMonthOrders = $this->getOrderDetails($userId, true, 'This Month');
        $lastMonthOrders = $this->getOrderDetails($userId, true, 'Last Month');
        return view('dashboards.restaurant.dashboard', compact('todayOrders', 'lastSevenDaysOrders', 'thisMonthOrders', 'lastMonthOrders'));
    }

    public function driverDashboard()
    {
        return view('dashboards.driver.dashboard');
    }

    public function supportDashboard()
    {
        return view('dashboards.support.dashboard');
    }

    public function customerDashboard()
    {
        return view('dashboards.customer.dashboard');
    }

    /**
     * Load all drivers who have orders payment.
     *
     * @param \Illuminate\Http\Response $request
     *
     * @return a object which is list of the drivers with delivery details and orders.
     *
     * */
    public function financeManagerDashboard(Request $request)
    {
        return view('dashboards.finance_manager.dashboard');
    }

    public function financeOfficerDashboard()
    {

        $paymentData = $this->getPaymentData();
        return view('dashboards.finance_officer.dashboard', compact('paymentData'));
    }

    public function getOrderDetails($userId = null, $count = false, $date = null, $forAdmin = false)
    {
        $branchIds = [];
        if ($userId != null) {
            // Get user branch.
            $branches = getUserBranches($userId);
            foreach ($branches as $branch) {
                array_push($branchIds, $branch->id);
            }
        }
        $query = Order::query();
        if (!$forAdmin) {
            $query = $query->WhereIn('branch_id', $branchIds);
        }

        if ($date == "Today") {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($date == "Last 7 Days") {
            $query->whereDate('created_at', '>', Carbon::now()->subDays(7));
        } elseif ($date == "This Month") {
            $query->whereMonth('created_at', Carbon::now()->month);
            $query->whereYear('created_at', date('Y'));
        } elseif ($date == "Last Month") {
            $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
            $query->whereYear('created_at', date('Y'));
        } elseif ($date == "Yesterday") {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($date == "Last Week") {
            $query->whereDate('created_at', '<', Carbon::now()->subDays(7));
            $query->whereDate('created_at', '>', Carbon::now()->subDays(14));
        } elseif ($date == "This Year") {
            $query->whereYear('created_at', date('Y'));
        } elseif ($date == "Last Year") {
            $query->whereYear('created_at', now()->subYear(1));
        }

        if ($count) {
            $orders = $query->count();
        } else {
            $orders = $query->get();
        }
        return $orders;
    }

    public function get_orders_by_status()
    {
        $chartData = [];
        $statuses = [
            'pending' => '#f27d09', 'completed' => '#00BCD4', 'reject' => '#E91E63',
            'processing' => '#4CAF50', 'delivered' => '#CDDC39', 'canceld' => '#f23409'
        ];
        $userId = Auth::user()->id;
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }
        foreach ($statuses as $key => $status) {
            array_push($chartData,  $this->CreateOrdersChart($key, $status, $key, $branchIds, (get_role() == "admin") ? true : false));
        }
        return response()->json($chartData);
    }

    public function CreateOrdersChart($status, $color, $title, $branchIds, $forAdmin = false)
    {
        $query = Order::where('status', $status);
        if (!$forAdmin) {
            $query = $query->WhereIn('branch_id', $branchIds);
        }
        $query = $query->count();
        $status = [
            ucfirst($title),
            $query,
            $color
        ];
        return $status;
    }

    public function adminDashboardData()
    {
        $first = date("Y-m-d", strtotime("today"));
        $last = date("Y-m-d", strtotime("tomorrow"));
        $today_orders = Order::whereBetween('created_at', [$first, $last]);
        $data = [
            'all_orders' => $today_orders->count(),
            'all_derlivered' => $today_orders->where('paid', 1)->count(),
            'total_income' => $today_orders->where('paid', 1)->sum('total'),
            'total_commission' => $today_orders->where('paid', 1)->sum('commission_value'),
            'totalRestaurants' => User::where('role_id', 4)->count(),
            'totalDrivers' => User::where('role_id', 3)->count(),
            'totalItems' => Item::count(),
            'totalOrders' => Order::count(),
            'todayOrders' => $this->getOrderDetails(null, true, 'Today', true),
            'yesterdayOrders' => $this->getOrderDetails(null, true, 'Yesterday', true),
            'thisMonthOrders' => $this->getOrderDetails(null, true, 'This Month', true),
            'lastMonthOrders' => $this->getOrderDetails(null, true, 'Last Month', true),
            'thisWeekOrders' => $this->getOrderDetails(null, true, 'Last 7 Days', true),
            'lastWeekOrders' => $this->getOrderDetails(null, true, 'Last Week', true),
            'thisYearOrders' => $this->getOrderDetails(null, true, 'This Year', true),
            'lastYearOrders' => $this->getOrderDetails(null, true, 'Last Year', true),
        ];
        return $data;
    }

    public function getPaymentData()
    {

        $data['totalPaidRestaurants'] = DB::table('payments')->distinct('branch_id')->count('branch_id');
        $data['totalPendingPayments'] = Payment::where('status', 'paid')->count();
        $data['totalActiveRestaurants'] = get_active_branches(true); // True is to take count.
        $data['totalActivePayments'] = Payment::where('status', 'activated')->count();

        $data['financial_summary']['total_order'] = Payment::sum('total_order');
        $data['financial_summary']['total_order_income'] = Payment::sum('total_order_income');
        $data['financial_summary']['total_paid'] = Payment::where('status', 'paid')->sum('total_order_income');
        $data['financial_summary']['total_received'] = Payment::where('status', 'done')->sum('total_order_income');


        return $data;
    }
    public function getRestaurantPaymentData()
    {
        date_default_timezone_set('Asia/Kabul');
        // date_default_timezone_set('Asia/Aden');

        $branches = Branch::with('users')->get();
        $chartData = [];
        foreach (range(1, 10) as $value) {
            $first = Carbon::now()->subDays($value);
            $past = Carbon::now()->subDays($value-1);
            $thisData = [];
            $thisData[] = $value;
            $labels = [];
            foreach ($branches as $key => $branch) {
                if($value == 10){
                    $labels[] = $branch->users->name;
                }
                $thisData[] = Payment::where('branch_id', $branch->id)->where('status', 'approved')->whereBetween('created_at', [$first, $past])->sum('total_order_income');
            }
            $chartData[] = $thisData;
        }
        return ['data' => $chartData, 'label'=> $labels];
    }
}
