<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;
class Counter extends Component
{
    public $listeners = ['refreshMenus' => '$refresh'];

    public $keyword;


    public function render()
    {
        $perPage = 10;
        $keyword = $this->keyword;
        if (!empty($keyword)) {
            $menu = Menu::where('title', 'LIKE', "%$keyword%")
                ->orWhere('status', 'LIKE', "%$keyword%")
                ->orWhere('items', 'LIKE', "%$keyword%")
                ->latest()->paginate($perPage);
        }
        else {
            $menu = Menu::latest()->paginate($perPage);
        }
        return view('livewire.counter', compact('menu'));

        // In case we want to use another layout.
    	// return view('livewire.counter')->extends('dashboards.restaurant.layouts.master');
    }
}
