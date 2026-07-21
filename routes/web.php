<?php

use Illuminate\Support\Facades\Route;
use Bale\BaleOrganisasi\Livewire\LandingPage\Index;
use Bale\BaleOrganisasi\Livewire\LandingPage\Post\PostList;
use Bale\BaleOrganisasi\Livewire\LandingPage\Post\PostShow;
use Bale\BaleOrganisasi\Livewire\LandingPage\Page\PageShow;
use Bale\BaleOrganisasi\Livewire\LandingPage\Ikm\IkmList;
use Bale\BaleOrganisasi\Livewire\LandingPage\Ikm\IkmDetail;
use Bale\BaleOrganisasi\Livewire\LandingPage\Regulasi\RegulasiList;

// Landing Page Routes
Route::middleware(['web'])->group(function () {
    Route::get('/', Index::class)->name('bale-organisasi.home');

    Route::prefix('berita')->name('bale-organisasi.post.')->group(function () {
        Route::get('/', PostList::class)->name('index');
        Route::get('/{slug}', PostShow::class)->name('show');
    });

    Route::get('/{slug}', PageShow::class)->name('bale-organisasi.page.show');

    Route::prefix('ikm')->name('bale-organisasi.ikm.')->group(function () {
        Route::get('/list', IkmList::class)->name('index');
        Route::get('/show/{id}', IkmDetail::class)->name('show');
    });

    Route::prefix('regulasi')->name('bale-organisasi.regulasi.')->group(function () {
        Route::get('/list', RegulasiList::class)->name('index');
    });
});