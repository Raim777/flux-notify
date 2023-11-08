<?php

use Raim\FluxNotify\Http\Controllers\NotificationController;
use Raim\FluxNotify\Http\Controllers\NotificationTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('unread', [NotificationController::class, 'getUnread']);
        Route::get('{slug}', [NotificationController::class, 'getUserNotifications']);
        Route::post('unread', [NotificationController::class, 'updateUnread']);
        Route::post('updateUnread', [NotificationController::class, 'updateUnread']);
    });
});

Route::apiResource('notification-types', NotificationTypeController::class)->only(['index']);