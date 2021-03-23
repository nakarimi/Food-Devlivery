<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ShowNotifications extends Component
{
    public $listeners = ['refreshNotifications'];

    public function render()
    {
        if (get_role() == "restaurant"){
            return view('livewire.restaurant.show-notifications')->extends('dashboards.restaurant.layouts.master');
        }
        return view('livewire.show-notifications');
    }

    public function refreshNotifications()
    {
     $this->emit('refresh');
    }
}
