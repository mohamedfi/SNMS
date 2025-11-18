<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public API routes
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'SNMS API is running',
        'version' => app()->version(),
        'timestamp' => now()->toIso8601String()
    ]);
});

// Protected API routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
