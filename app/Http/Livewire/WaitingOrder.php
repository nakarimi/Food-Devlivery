<?php

namespace App\Http\Livewire;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WaitingOrder extends Component
{
    public $listeners = ['refreshWaitingOrder' => '$refresh'];
    public $keyword;

    public function render()
    {
        // Get all wating orders, true (means realTime);
        return get_orders('waiting-orders', NULL, true);
    }
}
