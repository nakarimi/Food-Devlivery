<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardsController extends Controller
{
    public function restaurantDashboard()
    {
        $userId = Auth::user()->id;
        $todayOrders = $this->getOrderDetails($userId, true,'Today');
        $lastSevenDaysOrders = $this->getOrderDetails($userId, true,'Last 7 Days');
        $thisMonthOrders = $this->getOrderDetails($userId, true,'This Month');
        $lastMonthOrders = $this->getOrderDetails($userId, true,'Last Month');
        return view('dashboards.restaurant.dashboard', compact('todayOrders', 'lastSevenDaysOrders', 'thisMonthOrders','lastMonthOrders'));
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

    public function getOrderDetails($userId, $count = false, $date = null)
    {
        // Get user branch.
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }
        $query = Order::WhereIn('branch_id', $branchIds);
        if ($date == "Today"){
            $query->whereDate('created_at', Carbon::today());
        }
        elseif ($date == "Last 7 Days"){
            $query->whereDate('created_at', '>',Carbon::now()->subDays(7));
        }
        elseif ($date == "This Month"){
            $query->whereMonth('created_at', Carbon::now()->month);
        }
        elseif ($date = "Last Month"){
            $query->whereMonth('created_at', Carbon::now()->subMonth()->month);
        }

        if ($count){
            $orders = $query->count();
        }
        else {
            $orders = $query->get();
        }
        return $orders;
    }

    public function get_orders_by_status()
    {
        $chartData = [];
        $statuses = ['pending' => '#f27d09' , 'completed' => '#00BCD4', 'approved'=>'#FF9800', 'reject' => '#E91E63',
            'processing'=> '#4CAF50', 'delivered' => '#CDDC39', 'canceld' => '#f23409'];
        $userId = Auth::user()->id;
        $branches =  getUserBranches($userId);
        $branchIds = [];
        foreach ($branches as $branch) {
            array_push($branchIds, $branch->id);
        }
        foreach ($statuses as $key => $status){
           array_push($chartData,  $this->CreateOrdersChart($key, $status, $key, $branchIds));
        }
        return response()->json($chartData);
    }

    public function CreateOrdersChart($status, $color, $title, $branchIds)
    {
         $status = [
            ucfirst($title),
            Order::WhereIn('branch_id', $branchIds)->where('status', $status)->count(),
            $color ];
         return $status;
    }
}
