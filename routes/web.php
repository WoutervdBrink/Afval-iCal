<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
Route::post('/', [\App\Http\Controllers\HomeController::class, 'create'])->name('home.create');
Route::view('/privacy', 'privacy')->name('privacy');
Route::get('/{calendar}', [\App\Http\Controllers\HomeController::class, 'show'])->name('home.show');
Route::get('/ical/{calendar}', [\App\Http\Controllers\IcalController::class, 'render'])->name('ical.render');
