<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\NandaSearch;
use App\Livewire\NandaDetail;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'es'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

Route::get('/', NandaSearch::class)->name('home');
Route::get('/nanda/{nanda}', NandaDetail::class)->name('nanda.detail');
