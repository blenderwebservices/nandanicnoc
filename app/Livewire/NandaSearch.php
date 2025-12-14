<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class NandaSearch extends Component
{
    #[Url]
    public $search = '';

    public function render()
    {
        return view('livewire.nanda-search', [
            'nandas' => \App\Models\Nanda::query()
                ->when($this->search, function ($query) {
                    // Use FTS if search term is present
                    $query->whereIn('id', function ($subQuery) {
                        $subQuery->select('diagnosis_id')
                            ->from('nanda_search_index')
                            ->whereRaw("nanda_search_index MATCH ?", [$this->search . '*']);
                    });
                })
                ->with('nandaClass.domain') // Eager load relationships
                ->paginate(12),
        ]);
    }
}
