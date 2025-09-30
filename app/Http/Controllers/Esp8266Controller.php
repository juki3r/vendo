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
            ],
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


    //rates show
    public function createRates($id)
    {
        $esp = Esp8266::findOrFail($id);
        return view('esp8266s.create-rates', compact('esp'));
    }

    public function storeRates(Request $request, $id)
    {
        $esp = Esp8266::findOrFail($id);

        $request->validate([
            'coins.*'   => 'required|integer|min:1',
            'minutes.*' => 'required|integer|min:1',
        ]);

        $rates = [];
        foreach ($request->coins as $i => $coin) {
            $rates[$coin] = $request->minutes[$i];
        }

        $esp->rates = $rates;
        $esp->save();

        return redirect()->route('esp8266s.index')
            ->with('success', 'Rates added successfully!');
    }
}
