<?php

namespace App\Http\Livewire;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActiveOrder extends Component
{
    public $listeners = ['refreshActiveOrders' => '$refresh'];
    public $keyword;

    public function render()
    {
        $drivers = Driver::all();
        // If it is restaurant then user will have some restricted data.
//        dd(get_role());
        if (get_role() == "restaurant"){
            $userId = Auth::user()->id;
            $status = ['pending', 'approved', 'reject', 'processing', 'delivered'];
            $orders = loadUserAllOrders($userId, $status);
            return view('livewire.restaurant.active-orders', compact('orders', 'drivers'))->extends('dashboards.restaurant.layouts.master');
        }

        $orders = $this->get_orders('active-orders');
        return view('livewire.active-order', compact('orders', 'drivers'));
    }

    // General function to get orders, based on the provided params.
    public function get_orders($type) {
        // Order lists based on different status. active([Pending, Accept, Processing, Delivery]) and history ([completed, Canceled])
        $status = ($type == 'history') ? ['completed', 'canceld'] : ['pending', 'approved', 'reject', 'processing', 'delivered'];

        $keyword = $this->keyword;
        $perPage = 10;

        if (!empty($keyword)) {
            $orders = Order::whereIn('status', $status)->wherehas(
                'branchDetails', function ($query) use ($keyword) {
                $query->where('title','LIKE', "%$keyword%");
            })->orwhere('title', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        }
        else {
            $orders = Order::whereIn('status', $status)->latest()->paginate($perPage);
        }
        return $orders;

    }
}
