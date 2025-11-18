<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Students\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::prefix('v1')->group(function () {

    // Authentication routes (public)
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

        // Protected auth routes
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
            Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
            Route::put('/profile', [AuthController::class, 'updateProfile'])->name('auth.update-profile');
            Route::put('/password', [AuthController::class, 'changePassword'])->name('auth.change-password');
        });
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {

        // Student routes
        Route::apiResource('students', StudentController::class);
        Route::post('students/{student}/photo', [StudentController::class, 'uploadPhoto'])->name('students.upload-photo');
        Route::get('students/{student}/attendance-summary', [StudentController::class, 'attendanceSummary'])->name('students.attendance-summary');

        // Guardian routes
        // Route::apiResource('guardians', GuardianController::class);

        // Class routes
        // Route::apiResource('classes', ClassController::class);

        // Attendance routes
        // Route::post('attendance/students', [StudentAttendanceController::class, 'markAttendance']);
        // Route::get('attendance/students/{student}', [StudentAttendanceController::class, 'show']);
        // Route::get('attendance/classes/{class}', [StudentAttendanceController::class, 'classAttendance']);

        // Teacher Attendance routes
        // Route::post('attendance/teachers', [TeacherAttendanceController::class, 'markAttendance']);
        // Route::get('attendance/teachers/{user}', [TeacherAttendanceController::class, 'show']);

        // Invoice routes
        // Route::apiResource('invoices', InvoiceController::class);

        // Payment routes
        // Route::apiResource('payments', PaymentController::class);

        // Notification routes
        // Route::get('notifications', [NotificationController::class, 'index']);
        // Route::put('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);

    });
});
