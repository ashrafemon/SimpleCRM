<?php

use Illuminate\Support\Facades\Route;

Route::redirect('', 'login');
Route::get('login', fn() => inertia('Auth/Login'))->name('auth.login');

Route::get('dashboard', fn() => inertia('Panel/Dashboard'))->name('dashboard');
Route::get('leads', fn() => inertia('Panel/Leads'))->name('leads');
Route::get('applications', fn() => inertia('Panel/Applications'))->name('applications');
