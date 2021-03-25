<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WaitingOrder extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $listeners = ['refreshWaitingOrder'];
    public $keyword;

    public function render()
    {
        // Get all wating orders, true (means realTime);
        return get_orders('waiting-orders', NULL, true, $this->keyword);
    }

    // this name should be same as listener name
    // this function refresh data and reinitiliaze javascript files.
    public function refreshWaitingOrder()
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
