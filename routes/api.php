<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardApiController;
use App\Http\Controllers\Api\V1\DirectoryController;
use App\Http\Controllers\Api\V1\EventApiController;
use App\Http\Controllers\Api\V1\NotificationApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Publik
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Terproteksi Sanctum
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::get('/alumni', [DirectoryController::class, 'alumni']);
        Route::get('/alumni/{profile}', [DirectoryController::class, 'alumniShow']);
        Route::get('/businesses', [DirectoryController::class, 'businesses']);

        Route::get('/events', [EventApiController::class, 'index']);
        Route::get('/events/{event}', [EventApiController::class, 'show']);
        Route::post('/events/{event}/register', [EventApiController::class, 'register']);
        Route::get('/my-events', [EventApiController::class, 'myEvents']);

        Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);
        Route::get('/dashboard/map', [DashboardApiController::class, 'map']);
        Route::get('/recommendations', [DashboardApiController::class, 'recommendations']);

        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::post('/notifications/{notification}/read', [NotificationApiController::class, 'markRead']);
    });
});
