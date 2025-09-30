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

        $request->validate([
            'user_id' => 'required|string',
            'device_id' => 'required|string',
            'status' => 'required|string',
        ]);

        // // Find ESP device by API key
        $esp = Esp8266::where('user_id', $request->user_id)->first();

        // // Update device status (last_seen & status)
        $esp = Esp8266::updateOrCreate(
            ['device_id' => $request->device_id], // search condition
            [
                'user_id' => $request->user_id,
                'device_status' => $request->status,
                'last_seen' => now(),
            ]
        );



        if (!$esp) {
            return response()->json(['status' => 'error'], 401);
        }

        return response()->json([
            'status' => 'success',
            'api_key' => $apiKey,
            'user' => $user,
            'user_id' => $request->user_id,
            ''
        ]);
    }
}
