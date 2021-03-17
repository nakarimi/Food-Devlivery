<?php

namespace App\Http\Livewire;

use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ActiveOrder extends Component
{
    public $listeners = ['refreshActiveOrders'];
    public $keyword;

    public function render()
    {
       // Get all wating orders, true (means realTime);
       return get_orders('active-orders', NULL, true, $this->keyword);
    }

    // this name should be same as listener name
    // this function refresh data and reinitiliaze javascript files.
    public function refreshActiveOrders()
    {
        $this->emit('refresh');
        $this->addJs();
    }
    // this function reinitiliaze javascript files.
    public function addJs()
    {
        $this->dispatchBrowserEvent('reinitializaJSs');
    }
}
