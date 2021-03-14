<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;
class Counter extends Component
{
    public $count = 0;
    public $menu;

    public function increment()
    {
        $this->count++;
        $this->menu =  Menu::all();      
    }

    public function render()
    {
    	return view('livewire.counter', ['menu' => $this->menu]);
    }
}