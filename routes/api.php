<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\LeadMaintainerController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::middleware(['auth:api', 'tokenChecker'])->group(function () {
    Route::apiResource('users', UserController::class)->except(['create', 'edit']);
    Route::apiResource('leads', LeadController::class)->except(['create', 'edit']);
    Route::apiResource('lead-maintainers', LeadMaintainerController::class)->except(['create', 'edit']);
    Route::apiResource('applications', ApplicationController::class)->except(['create', 'edit']);
});
