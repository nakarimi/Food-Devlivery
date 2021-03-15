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
        // Get all wating orders, true (means realTime);
        return get_orders('active-orders', NULL, true);
    }
}
