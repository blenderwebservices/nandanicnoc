<?php

namespace App\Livewire;

use Livewire\Component;

class NandaDetail extends Component
{
    public \App\Models\Nanda $nanda;

    public function render()
    {
        return view('livewire.nanda-detail');
    }
}
