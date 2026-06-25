<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IrrigationMode;

class IrrigationModeController extends Controller
{
    //
    public function getMode(Request $request)
    {

        $token = $request->query('token');
        if ($token !== config('app.irrigation_token')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        

        $mode = IrrigationMode::first();

        if (!$mode) {
            $mode = IrrigationMode::create([
                'mode' => 'manual'
            ]);
        }

        return response()->json([
            'mode' => $mode->mode
        ]);
    }

    public function setMode(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:manual,timer,automatic'
        ]);

        $mode = IrrigationMode::first();

        if (!$mode) {
            $mode = new IrrigationMode();
        }

        $mode->mode = $request->mode;
        $mode->save();

        return response()->json([
            'success' => true,
            'mode' => $mode->mode
        ]);
    }
}
