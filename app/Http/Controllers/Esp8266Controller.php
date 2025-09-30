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

        // Find user by API key
        $user = User::where('api_key', $apiKey)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        // Validate request body
        $request->validate([
            'user_id' => 'required|integer',   // match your users.id type
            'device_id' => 'required|string',
            'status' => 'required|string',
        ]);

        // Update or create ESP record
        $esp = Esp8266::updateOrCreate(
            ['device_id' => $request->device_id],
            [
                'user_id' => $request->user_id,
                'device_status' => $request->status,
                'last_seen' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'api_key' => $apiKey,
            'user_id' => $user->id,
            'device_status' => $esp->device_status,
            'last_seen' => $esp->last_seen,
            'rates' => [
                '1' => 10,
                '5' => 120,
                '10' => 240
            ],
        ]);
    }
}
