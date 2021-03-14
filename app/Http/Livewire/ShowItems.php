<?php

namespace App\Http\Livewire;

use App\Models\Item;
use Livewire\Component;

class ShowItems extends Component
{
    public $listeners = ['refreshItems' => '$refresh'];

    public function render()
    {

        $item = Item::latest()->get();
        return view('livewire.show-items', compact('item'));
    }

    public function refreshComponent()
    {
        $this->emit('refreshItems');
    }

}
