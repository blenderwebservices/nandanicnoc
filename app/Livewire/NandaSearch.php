<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class NandaSearch extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.nanda-search', [
            'nandas' => \App\Models\Nanda::query()
                ->when($this->search, function ($query) {
                    // Sanitize search term for FTS5: escape quotes and wrap in quotes for phrase search
                    $searchTerm = str_replace('"', '""', $this->search);

                    // Use FTS if search term is present
                    $query->whereIn('id', function ($subQuery) use ($searchTerm) {
                        $subQuery->select('diagnosis_id')
                            ->from('nanda_search_index')
                            ->whereRaw('nanda_search_index MATCH ?', ['"' . $searchTerm . '"*']);
                    });
                })
                ->with('nandaClass.domain') // Eager load relationships
                ->paginate(12),
        ]);
    }
}
