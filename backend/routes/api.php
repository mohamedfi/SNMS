<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

// Public API routes
Route::get('/', function () {
    return response()->json(['Laravel' => app()->version()])
        ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
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

// Students API routes
Route::apiResource('students', StudentController::class);

// Teachers API routes
Route::apiResource('teachers', TeacherController::class);

// Protected API routes
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
