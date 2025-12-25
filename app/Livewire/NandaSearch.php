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
        $nandasQuery = \App\Models\Nanda::query()
            ->when($this->search, function ($query) {
                $searchTerm = str_replace('"', '""', $this->search);
                $query->whereIn('nandas.id', function ($subQuery) use ($searchTerm) {
                    $subQuery->select('diagnosis_id')
                        ->from('nanda_search_index')
                        ->whereRaw('nanda_search_index MATCH ?', ['"' . $searchTerm . '"*']);
                });
            });

        // Get keys and counts of domains based on current search
        $domainStats = \App\Models\Nanda::query()
            ->when($this->search, function ($query) {
                $searchTerm = str_replace('"', '""', $this->search);
                $query->whereIn('nandas.id', function ($subQuery) use ($searchTerm) {
                    $subQuery->select('diagnosis_id')
                        ->from('nanda_search_index')
                        ->whereRaw('nanda_search_index MATCH ?', ['"' . $searchTerm . '"*']);
                });
            })
            ->join('nanda_classes', 'nandas.class_id', '=', 'nanda_classes.id')
            ->selectRaw('nanda_classes.domain_id, count(*) as count')
            ->groupBy('nanda_classes.domain_id')
            ->pluck('count', 'nanda_classes.domain_id');

        $domains = \App\Models\Domain::whereIn('id', $domainStats->keys())
            ->orderBy('code')
            ->get()
            ->map(function ($domain) use ($domainStats) {
                $domain->count = $domainStats[$domain->id] ?? 0;
                return $domain;
            });

        // Get suggestions for the dropdown
        $suggestions = [];
        if (strlen($this->search) >= 2) {
            $column = app()->getLocale() === 'es' ? 'diagnosis_label_es' : 'diagnosis_label';
            $searchTerm = str_replace('"', '""', $this->search);

            $suggestions = \DB::table('nanda_search_index')
                ->whereRaw("nanda_search_index MATCH ?", ['"' . $searchTerm . '"*'])
                ->select($column . ' as label')
                ->distinct()
                ->limit(5)
                ->pluck('label')
                ->toArray();
        }

        return view('livewire.nanda-search', [
            'nandas' => $nandasQuery->with('nandaClass.domain')->paginate(12),
            'domains' => $domains,
            'suggestions' => $suggestions,
        ]);
    }
}
