<?php

use App\Http\Middleware\VeryBasicAuth;
use App\Livewire\AttendanceGraphs;
use App\Livewire\PastoralGraphs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Wing5wong\KamarDirectoryServices\Models\Pastoral;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/attendance', AttendanceGraphs::class)
    ->middleware(VeryBasicAuth::class)
    ->name('attendance');

Route::get('/pastorals', PastoralGraphs::class)
    ->middleware(VeryBasicAuth::class)
    ->name('pastorals');

Route::get('/recognitions', function () {
    return view('recognitions');
})->name('recognitions');
