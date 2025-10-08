<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sales;
use App\Models\Esp8266;
use App\Models\ActiveClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $user = User::where('api_key', $apiKey)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'user_id'    => 'required|integer',
            'device_id'  => 'required|string',
            'status'     => 'required|string',
        ]);

        $esp = Esp8266::updateOrCreate(
            ['device_id' => $request->device_id],
            [
                'user_id'       => $request->user_id,
                'device_status' => $request->status,
                'last_seen'     => now(),
            ]
        );

        // If rates is empty, set default
        if (empty($esp->rates)) {
            $esp->rates = [
                '1'  => 10,
                '5'  => 120,
                '10' => 240,
            ];
            $esp->save();
        }

        return response()->json([
            'status'        => 'success',
            'api_key'       => $apiKey,
            'user_id'       => $user->id,
            'device_status' => $esp->device_status,
            'last_seen'     => $esp->last_seen,
            'rates'         => $esp->rates,   // <-- no json_decode here
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

    public function storeVendoSales(Request $request)
    {
        $data = $request->validate([
            'voucher'    => 'required|string',
            'minutes'    => 'required|integer',
            'coins'      => 'required|integer',
            'ip'         => 'nullable|string',
            'mac'        => 'nullable|string',
            'device_id'  => 'required|string',
            'user_id'    => 'required|integer',
        ]);

        $sale = Sales::create($data);

        return response()->json([
            'success' => true,
            'sale'    => $sale,
        ], 201);
    }

    // //Save Active clients belong to device made
    public function storeActiveClients(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'user_id'   => 'required|string',
            'clients'   => 'required|array',
            'clients.*.username' => 'required|string',
            'clients.*.ip'       => 'required|string',
            'clients.*.mac'      => 'required|string',
            'clients.*.uptime'   => 'required|string',
            'clients.*.remaining_seconds'   => 'required|string',
        ]);



        foreach ($request->clients as $client) {
            ActiveClient::updateOrCreate(
                [
                    'device_id' => $request->device_id,
                    'username'  => explode('|', $client['username'])[0],
                ],
                [
                    'user_id' => $request->user_id,
                    'ip'      => explode('|', $client['ip'])[0],
                    'mac'     => explode('|', $client['mac'])[0],
                    'uptime'  => explode('|', $client['uptime'])[0],
                    'uptime'  => explode('|', $client['remaining_seconds'])[0],
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Active clients saved',
        ]);
    }
}
