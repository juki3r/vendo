<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Esp8266;
use Illuminate\Http\Request;

class Esp8266Controller extends Controller
{
    // public function onlineCheck(Request $request)
    // {
    //     $apiKey = $request->header('X-API-KEY');

    //     // Find user by API key
    //     $user = User::where('api_key', $apiKey)->first();

    //     if (!$user) {
    //         return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    //     }

    //     // Validate request body
    //     $request->validate([
    //         'user_id' => 'required|integer',   // match your users.id type
    //         'device_id' => 'required|string',
    //         'status' => 'required|string',
    //     ]);

    //     // Update or create ESP record
    //     $esp = Esp8266::updateOrCreate(
    //         ['device_id' => $request->device_id],
    //         [
    //             'user_id' => $request->user_id,
    //             'device_status' => $request->status,
    //             'last_seen' => now(),
    //         ]
    //     );

    //     return response()->json([
    //         'status' => 'success',
    //         'api_key' => $apiKey,
    //         'user_id' => $user->id,
    //         'device_status' => $esp->device_status,
    //         'last_seen' => $esp->last_seen,
    //         'rates' => [
    //             '1' => 10,
    //             '5' => 120,
    //         ],
    //     ]);
    // }

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
            'user_id' => 'required|integer',
            'device_id' => 'required|string',
            'status' => 'required|string',
        ]);

        // Update or create ESP record
        $esp = Esp8266::updateOrCreate(
            ['device_id' => $request->device_id],
            [
                'user_id'       => $request->user_id,
                'device_status' => $request->status,
                'last_seen'     => now(),
            ]
        );

        // If rates is empty, set defaults
        if (empty($esp->rates)) {
            $esp->rates = json_encode([
                '1'  => 10,   // ₱1 = 10 minutes
                '5'  => 120,  // ₱5 = 120 minutes
                '10' => 240,  // ₱10 = 240 minutes
            ]);
            $esp->save();
        }

        return response()->json([
            'status'        => 'success',
            'api_key'       => $apiKey,
            'user_id'       => $user->id,
            'device_status' => $esp->device_status,
            'last_seen'     => $esp->last_seen,
            'rates'         => json_decode($esp->rates, true),
        ]);
    }


    //response from esp
    public function responseUpdate(Request $request)
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
            'response_update' => 'required|string',
        ]);

        //Update where the response_update is.
    }
}
