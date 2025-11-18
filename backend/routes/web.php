<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Steps Nursery Management System API',
        'version' => '1.0.0',
        'endpoints' => [
            'api' => url('/api/v1'),
            'health' => url('/up'),
        ],
    ]);
});
