<?php

use Illuminate\Support\Facades\Route;
use Wing5wong\KamarDirectoryService\Controllers\HandleKamarPost;

Route::middleware('kamar')->post('/kamar', HandleKamarPost::class)->name('kamar');