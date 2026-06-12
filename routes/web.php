<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->get('/', function () {
    return view('pages.welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::post('/logout', function () {
        auth()->logout();

        return redirect()->route('welcome');
    })->name('logout');
});
