<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobCostController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobRevenueController;
use App\Http\Controllers\JobAdjustmentController;
use App\Http\Controllers\JobTransportController;

Route::prefix('clients')->group(function () {
    Route::get('/', [ClientController::class, 'index']);
    Route::post('/', [ClientController::class, 'store']);
    Route::get('/{id}', [ClientController::class, 'show']);
    Route::put('/{id}', [ClientController::class, 'update']);
    Route::delete('/{id}', [ClientController::class, 'destroy']);
    Route::get('/user/{userId}', [ClientController::class, 'byUser']);
});

Route::prefix('jobs')->group(function () {
    Route::get('/', [JobController::class, 'index']);
    Route::post('/', [JobController::class, 'store']);
    Route::get('/{id}', [JobController::class, 'show']);
    Route::put('/{id}', [JobController::class, 'update']);
    Route::delete('/{id}', [JobController::class, 'destroy']);
    Route::patch('/{id}/status', [JobController::class, 'updateStatus']);
});

Route::prefix('jobs/{job}/costs')->group(function () {
    Route::get('/', [JobCostController::class, 'index']);
    Route::post('/', [JobCostController::class, 'store']);
    Route::put('/{id}', [JobCostController::class, 'update']);
    Route::delete('/{id}', [JobCostController::class, 'destroy']);
});

Route::prefix('jobs/{job}/revenue')->group(function () {
    Route::get('/', [JobRevenueController::class, 'index']);
    Route::post('/', [JobRevenueController::class, 'store']);
    Route::put('/{id}', [JobRevenueController::class, 'update']);
    Route::delete('/{id}', [JobRevenueController::class, 'destroy']);
});

Route::prefix('jobs/{job}/adjustments')->group(function () {
    Route::get('/', [JobAdjustmentController::class, 'index']);
    Route::post('/', [JobAdjustmentController::class, 'store']);
});

Route::prefix('jobs/{job}/transports')->group(function () {
    Route::get('/', [JobTransportController::class, 'index']);
    Route::post('/', [JobTransportController::class, 'store']);
    Route::put('/{id}', [JobTransportController::class, 'update']);
    Route::delete('/{id}', [JobTransportController::class, 'destroy']);
});

