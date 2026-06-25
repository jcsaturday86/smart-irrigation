<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;

class CropMoistureController extends Controller
{
    public function index(Request $request)
    {

        $token = $request->query('token');
        if ($token !== config('app.irrigation_token')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        
        $crop = Crop::where('state', '1')->first();

        if (!$crop) {
            return response()->json([
                'success' => false,
                'message' => 'No active crop found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'crop_name' => $crop->crop_name,
                'moisture_min' => $crop->moisture_min,
                'moisture_max' => $crop->moisture_max,
                'state' => $crop->state,
            ]
        ]);
    }
}