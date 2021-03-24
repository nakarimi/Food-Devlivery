<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Item;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       Schema::defaultStringLength(191);

       // Share data for sidebar badges for restaurant and admin.
       View::composer(['layouts.sidebar', 'dashboards.restaurant.layouts.sidebar'], function ($view) {
            $status = ['pending', 'approved', 'reject', 'processing', 'delivered'];
            if (get_role() == "restaurant"){
                $userId = auth()->user()->id;
                $pendingItems = loadUserItemsData(['pending'], $userId, true);
                // Get user branch.
                $branches =  getUserBranches($userId);
                $branchIds = [];
                foreach ($branches as $branch) {
                    array_push($branchIds, $branch->id);
                }
                // Get user active orders.
                $activeOrders = Order::whereIn('branch_id', $branchIds)->whereIn('status', $status)->count();

                $view->with('sidebarData', [
                    'pendingItems' => $pendingItems,
                    'activeOrders' => $activeOrders
                ]);
            }
            else {
                // Get orders from 10 minutes ago.
                $timeOffSet = Carbon::now()->subMinutes(1)->toDateTimeString();
                $waitingOrders = Order::where(function ($query) use ($timeOffSet) {
                    $query->where('status', 'pending')->where('orders.created_at', '<', $timeOffSet);
                })->orwhere(function ( $query ) {
                    $query->whereHas('deliveryDetails', function ($subquery) {
                        $subquery->where('delivery_type', 'company')->whereNull('driver_id');
                    });
                })->count();

                // Get all active orders.
                $activeOrders = Order::whereIn('status', $status)->count();
                $pendingItems = Item::whereHas(
                    'itemFullDetails', function ($query) {
                    $query->where('details_status', '=', 'pending');
                })->count();

                // Get pending branches.
                $pendingBranches = Branch::whereHas(
                    'branchFullDetails', function ($query) {
                    $query->where('status', '=', 'pending');
                })->count();

                $view->with('sidebarData', [
                    'waitingOrders' => $waitingOrders,
                    'activeOrders' => $activeOrders,
                    'pendingItems' => $pendingItems,
                    'pendingBranches' => $pendingBranches
                ]);
            }
        });

       Paginator::useBootstrap();
    }

}
