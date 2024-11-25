<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home.index');
Route::post('/', [\App\Http\Controllers\HomeController::class, 'create'])->name('home.create');
Route::view('/privacy', 'privacy')->name('privacy');
Route::get('/{calendar}', [\App\Http\Controllers\HomeController::class, 'show'])->name('home.show');
Route::get('/ical/{calendar}', \App\Http\Controllers\IcalController::class)->name('ical.render');
