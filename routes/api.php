<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Claims
    Route::post('/claims', [ClaimController::class, 'store']);
    Route::get('/claims', [ClaimController::class, 'index']);
    Route::get('/claims/{id}', [ClaimController::class, 'show']);
    Route::put('/claims/{id}', [ClaimController::class, 'update']);
    Route::delete('/claims/{id}', [ClaimController::class, 'destroy']);

    // Approvals
    Route::get('/approvals', [ApprovalController::class, 'getPendingApprovals']);
    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve']);
    Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Reports
    Route::get('/reports', [ReportController::class, 'generateReport']);
});