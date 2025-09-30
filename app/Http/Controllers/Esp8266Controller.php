<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Esp8266Controller extends Controller
{
    public function onlineCheck(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
        ]);

        // Save/update device status
        $device = Device::updateOrCreate(
            ['device_id' => $request->device_id],
            ['last_seen' => now()]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Ping received',
            'last_seen' => $device->last_seen
        ]);
    }
}
