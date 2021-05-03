<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class DriversTracking extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $listeners = ['refreshDrivers'];
    public $keyword;
    public $code;

    public function render()
    {
        $drivers_data = DB::table('orders')
        ->join('order_delivery', 'orders.id', '=', 'order_delivery.order_id')
        ->join('drivers', 'drivers.id', '=', 'order_delivery.driver_id')
        ->whereDate('orders.created_at', Carbon::today())
        ->whereNotNull('driver_id')
        ->groupBy('driver_id')
        ->select('driver_id', 'drivers.title', 'contact', 'drivers.status', DB::raw('count(orders.id) as total'))
        ->get();
        
        return view('livewire.driver.drivers-tracking', compact('drivers_data'));
    }

    // this name should be same as listener name
    // this function refresh data and reinitiliaze javascript files.
    public function refreshDrivers()
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
