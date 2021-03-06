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

            if (get_role() == "restaurant"){
                
                $userId = auth()->user()->id;
                $pendingItems = loadUserItemsData(['pending'], $userId, true);
                
                // Get user branch.
                $branchID = get_current_branch_id();
                
                // Get user active orders.
                $activeOrders = get_active_orders_count($branchID);

                // Get user rejected items.
                $rejectedItems = loadUserItemsData(['rejected'], $userId, true);

                $view->with('sidebarData', [
                    'pendingItems' => $pendingItems,
                    'restaurantActiveOrders' => $activeOrders,
                    'rejectedItems' => $rejectedItems
                ]);
            }
            else {
                // Get orders from 10 minutes ago.
                $timeOffSet = Carbon::now()->subMinutes(1)->toDateTimeString();

                $waitingOrders = get_waiting_orders("%", null, true);

                // Get all active orders.
                $activeOrders = get_active_orders_count(false);
                
                $pendingItems = Item::whereHas(
                    'itemFullDetails', function ($query) {
                    $query->where('details_status', '=', 'pending');
                })->count();

                // Get pending branches.
                $pendingBranches = Branch::whereHas(
                    'branchFullDetails', function ($query) {
                    $query->where('status', '=', 'pending');
                })->count();

                $rejectedBranches = Branch::whereHas(
                    'branchFullDetails', function ($query) {
                    $query->where('status', '=', 'rejected');
                })->count();

                $rejectedItems = Item::whereHas(
                    'itemFullDetails', function ($query) {
                    $query->where('details_status', '=', 'rejected');
                })->count();

                $view->with('sidebarData', [
                    'waitingOrders' => $waitingOrders,
                    'activeOrders' => $activeOrders,
                    'pendingItems' => $pendingItems,
                    'pendingBranches' => $pendingBranches,
                    'rejectedBranches' => $rejectedBranches, 
                    'rejectedItems' => $rejectedItems
                ]);
            }
        });

       Paginator::useBootstrap();
    }

}
