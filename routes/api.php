<?php
// routes/api.php

use App\Http\Controllers\API\RequestController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Request routes
    Route::apiResource('requests', RequestController::class);
    Route::patch('/requests/{id}/approve', [RequestController::class, 'approve']);
    Route::patch('/requests/{id}/reject', [RequestController::class, 'reject']);

    // Auth routes
    Route::get('/user', function (\Illuminate\Http\Request $request) {
        return $request->user();
    });
});
