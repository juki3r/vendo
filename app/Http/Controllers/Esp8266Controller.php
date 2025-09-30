<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Esp8266;
use Illuminate\Http\Request;

class Esp8266Controller extends Controller
{
    public function onlineCheck(Request $request)
    {
        $apiKey = $request->header('X-API-KEY');
        // // Find user/device by API key
        $user = User::where('api_key', $apiKey)->first();

        return response()->json([
            'status' => 'success',
            'api_key' => $apiKey,
            'user' => $user,
        ]);

        // $apiKey = $request->header('X-API-KEY');

        // // Find user/device by API key
        // $user = User::where('api_key', $apiKey)->first();

        // if (!$user) {
        //     return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        // }

        // $request->validate([
        //     'user_id' => 'required|string',
        //     'device_id' => 'required|string',
        //     'status' => 'required|string',
        // ]);

        // // Find ESP device by API key
        // $esp = Esp8266::where('device_id', $request->device_id)->first();

        // // Update device status (last_seen & status)
        // $esp->updateOrCreate([
        //     'user_id' => $request->user_id,
        //     'device_id' => $request->device_id,
        //     'last_seen' => now(),
        //     'device_status' => $request->status, // make sure you have a column for this
        // ]);

        // return response()->json([
        //     'status' => 'success',
        //     'last_seen' => $user->last_seen,
        //     'device_status' => $user->device_status
        // ]);
    }
}
