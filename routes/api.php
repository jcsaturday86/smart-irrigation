<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IrrigationTimerController;
use App\Http\Controllers\Api\CropMoistureController;
use App\Http\Controllers\Api\IrrigationModeController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Simple API to control irrigation
Route::get('/irrigation', function (Request $request) {
    //get Token
    $tokenManualIrrigation = \App\Models\ManualIrrigationSettings::where('action','manual_irrigation')->first();
    //$tokenResult = $tokenManualIrrigation->token;

    // Optional token security
    $token = $request->query('token');
    if ($token !== config('app.irrigation_token')) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Get irrigation status (can come from DB or static)
    // Example: store status in a simple DB table "settings"
    $status = \App\Models\ManualIrrigationSettings::where('action', 'manual_irrigation')->value('value') ?? 'OFF';

    return response()->json([
        'status' => $status // ON / OFF
    ]);
});


Route::get('/irrigation-timer', [IrrigationTimerController::class, 'index']);
Route::post('/irrigation-timer', [IrrigationTimerController::class, 'store']);
Route::post('/irrigation-timer/stop', [IrrigationTimerController::class, 'stop']);
Route::get('/irrigation-timer/check', [IrrigationTimerController::class, 'check']);
Route::post('/irrigation-timer/update-last-run', [IrrigationTimerController::class, 'updateLastRun']);


Route::get('/crop-moisture', [CropMoistureController::class, 'index']);



Route::get('/irrigation-mode', [IrrigationModeController::class, 'getMode']);
Route::post('/irrigation-mode', [IrrigationModeController::class, 'setMode']);