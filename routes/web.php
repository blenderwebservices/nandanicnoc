<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\NandaSearch;
use App\Livewire\NandaDetail;

Route::get('/', NandaSearch::class)->name('home');
Route::get('/nanda/{nanda}', NandaDetail::class)->name('nanda.detail');
