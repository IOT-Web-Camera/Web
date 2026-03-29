<?php

use App\Http\Controllers\CameraEventController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\CommandController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/camera/event', [CameraEventController::class, 'handle']);
    Route::get('/camera/events/{device}', [CameraEventController::class, 'list']);

    Route::post('/camera/heartbeat', [CameraController::class, 'heartbeat']);
    Route::post('/camera/status', [CameraController::class, 'cameraStatus']);

    Route::get('/bridge/status', [CommandController::class, 'bridgeStatus']);
});


Route::post('/mediamtx/auth', function (Request $request) {
    return response()->json(['ok' => true]);
});


