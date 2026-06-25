<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IrrigationTimerSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IrrigationTimerController extends Controller
{
    public function index()
    {
        $timer = IrrigationTimerSetting::latest()->first();

        if (!$timer) {
            $timer = IrrigationTimerSetting::create([
                'duration_minutes' => 1,
                'frequency_hours' => 1,
                'is_active' => false,
                'state' => 'Inactive',
            ]);
        }

        $nextRun = null;

        if ($timer->is_active && $timer->last_run_at) {
            $nextRun = Carbon::parse($timer->last_run_at)
                ->addHours($timer->frequency_hours)
                ->toDateTimeString();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'duration_minutes' => $timer->duration_minutes,
                'frequency_hours' => $timer->frequency_hours,
                'last_run_at' => $timer->last_run_at?->toDateTimeString(),
                'is_active' => $timer->is_active,
                'state' => $timer->state,
                'next_run_at' => $nextRun,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'duration_minutes' => 'required|integer|min:1|max:180',
            'frequency_hours' => 'required|integer|min:1|max:24',
        ]);

        $timer = IrrigationTimerSetting::latest()->first();

        if (!$timer) {
            $timer = new IrrigationTimerSetting();
        }

        $timer->duration_minutes = $request->duration_minutes;
        $timer->frequency_hours = $request->frequency_hours;
        $timer->is_active = true;
        $timer->state = 'Active';
        $timer->save();

        return response()->json([
            'success' => true,
            'message' => 'Timer settings saved successfully.',
            'data' => $timer
        ]);
    }

    public function stop()
    {
        $timer = IrrigationTimerSetting::latest()->first();

        if (!$timer) {
            return response()->json([
                'success' => false,
                'message' => 'No timer found.'
            ], 404);
        }

        $timer->is_active = false;
        $timer->state = 'Inactive';
        $timer->save();

        return response()->json([
            'success' => true,
            'message' => 'Irrigation stopped successfully.',
            'data' => $timer,
            'state' => $timer->state,
        ]);
    }

    public function check(Request $request)
    {
        $token = $request->query('token');
        if ($token !== config('app.irrigation_token')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $timer = IrrigationTimerSetting::latest()->first();

        if (!$timer || !$timer->is_active) {
            return response()->json([
                'success' => true,
                'should_run' => false,
                'message' => 'Timer inactive.',
                'state' => $timer->state,
            ]);
        }

        $now = now();

        if (is_null($timer->last_run_at)) {
            $timer->last_run_at = $now;
            $timer->save();

            return response()->json([
                'success' => true,
                'should_run' => true,
                //'duration_minutes' => $timer->duration_minutes,
                'duration_seconds' => $timer->duration_minutes,
                'message' => 'First irrigation run.',
                'state' => $timer->state,
            ]);
        }

        //$nextRun = Carbon::parse($timer->last_run_at)->addHours($timer->frequency_hours);
        $nextRun = Carbon::parse($timer->last_run_at)->addMinutes($timer->frequency_hours);

        if ($now->greaterThanOrEqualTo($nextRun)) {
            $timer->last_run_at = $now;
            $timer->save();

            return response()->json([
                'success' => true,
                'should_run' => true,
                'duration_minutes' => $timer->duration_minutes,
                'message' => 'Scheduled irrigation run.',
                'state' => $timer->state,
            ]);
        }

        return response()->json([
            'success' => true,
            'should_run' => false,
            'next_run_at' => $nextRun->toDateTimeString(),
            'message' => 'Not yet time.',
            'state' => $timer->state,
        ]);
    }

    public function updateLastRun()
    {
        $timer = IrrigationTimerSetting::latest()->first();

        if (!$timer) {
            return response()->json([
                'success' => false,
                'message' => 'No timer found.'
            ], 404);
        }

        $timer->last_run_at = now();
        $timer->save();

        return response()->json([
            'success' => true,
            'message' => 'Last run updated.',
        ]);
    }
}