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
    // public function storeActiveClients(Request $request)
    // {
    //     $request->validate([
    //         'device_id' => 'required|string',
    //         'user_id'   => 'required|string',
    //         'clients'   => 'required|array',
    //         'clients.*.username' => 'required|string',
    //         'clients.*.ip'       => 'required|string',
    //         'clients.*.mac'      => 'required|string',
    //         'clients.*.uptime'   => 'required|string',
    //         'clients.*.remaining_seconds'   => 'required|string',
    //     ]);



    //     foreach ($request->clients as $client) {
    //         ActiveClient::updateOrCreate(
    //             [
    //                 'device_id' => $request->device_id,
    //                 'username'  => explode('|', $client['username'])[0],
    //             ],
    //             [
    //                 'user_id' => $request->user_id,
    //                 'ip'      => explode('|', $client['ip'])[0],
    //                 'mac'     => explode('|', $client['mac'])[0],
    //                 'uptime'  => explode('|', $client['uptime'])[0],
    //                 'uptime'  => explode('|', $client['remaining_seconds'])[0],
    //             ]
    //         );
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Active clients saved',
    //     ]);
    // }


    public function storeActiveClients(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
            'user_id'   => 'required|string',
            // allow null/empty array so "no clients" will clear DB
            'clients'   => 'nullable|array',
            // only required when clients array is present
            'clients.*.username'       => 'required_with:clients|string',
            'clients.*.ip'             => 'required_with:clients|string',
            'clients.*.mac'            => 'required_with:clients|string',
            'clients.*.remaining_time' => 'required_with:clients|string',
        ]);

        $deviceId = $request->device_id;
        $userId = $request->user_id;
        $clients = $request->clients ?? [];

        try {
            DB::transaction(function () use ($deviceId, $userId, $clients) {
                // 1) Delete existing for this device (use user_id if you want to be more specific)
                ActiveClient::where('device_id', $deviceId)->delete();

                // 2) If no clients, we're done (table already cleared)
                if (empty($clients)) {
                    return;
                }

                $now = now();
                $rows = [];

                foreach ($clients as $client) {
                    $username = explode('|', $client['username'])[0] ?? ($client['username'] ?? null);
                    $ip = explode('|', $client['ip'])[0] ?? ($client['ip'] ?? null);
                    $mac = explode('|', $client['mac'])[0] ?? ($client['mac'] ?? null);

                    $raw = $client['remaining_time'] ?? '';
                    $uptime = explode('|', $raw)[0] ?? '0s';

                    // Try to extract "=session-time-left=1h58m10s" pattern first
                    $remainingStr = '0s';
                    if (preg_match('/=session-time-left=([0-9hms]+)/', $raw, $m)) {
                        $remainingStr = $m[1];
                    } else {
                        // fallback: maybe raw itself is "1h58m10s" or numeric seconds
                        $remainingStr = $raw ?: '0s';
                    }

                    // convert to integer seconds (safe for INT DB column)
                    $remainingSeconds = $this->durationToSeconds($remainingStr);

                    $rows[] = [
                        'device_id' => $deviceId,
                        'user_id' => $userId,
                        'username' => $username,
                        'ip' => $ip,
                        'mac' => $mac,
                        'uptime' => $uptime,
                        'remaining_seconds' => $remainingSeconds,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                // bulk insert (avoids fillable/mass-assignment issues)
                ActiveClient::insert($rows);
            });
        } catch (\Throwable $e) {
            // log full payload and exception for debugging
            Log::error('storeActiveClients failed: ' . $e->getMessage(), [
                'exception' => $e,
                'payload' => $request->all(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to store active clients: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Active clients replaced successfully.',
        ]);
    }

    /**
     * Accepts strings like "1h58m10s", "58m10s", "10s", or numeric seconds and returns int seconds.
     */
    private function durationToSeconds($str)
    {
        if (is_numeric($str)) {
            return (int) $str;
        }

        $hours = 0;
        $minutes = 0;
        $seconds = 0;
        if (preg_match('/(\d+)h/', $str, $m)) $hours = (int)$m[1];
        if (preg_match('/(\d+)m/', $str, $m)) $minutes = (int)$m[1];
        if (preg_match('/(\d+)s/', $str, $m)) $seconds = (int)$m[1];

        return $hours * 3600 + $minutes * 60 + $seconds;
    }
}
