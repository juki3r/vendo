<?php

namespace App\Http\Controllers;

use App\Models\Esp8266;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function showRates()
    {
        // Get all ESP8266 devices belonging to the authenticated user
        $esps = Esp8266::where('user_id', Auth::id())->get();

        return view('esp8266s.index', compact('esps'));
    }

    public function storeRate(Request $request, $espId)
    {
        $request->validate([
            'coin'    => 'required|numeric|min:1',
            'minutes' => 'required|integer|min:1',
        ]);

        $esp = Esp8266::where('user_id', Auth::id())
            ->findOrFail($espId);

        $rates = json_decode($esp->rates ?? '{}', true);
        $rates[$request->coin] = $request->minutes;

        $esp->rates = json_encode($rates);
        $esp->save();

        return back()->with('success', 'Rate added successfully!');
    }
    // Update a rate
    public function updateRate(Request $request, $espId, $coin)
    {
        $request->validate([
            'minutes' => 'required|integer|min:1',
        ]);

        $esp = Esp8266::where('user_id', Auth::id())
            ->findOrFail($espId);

        $rates = json_decode($esp->rates ?? '{}', true);

        if (!isset($rates[$coin])) {
            return back()->with('error', 'Rate not found.');
        }

        $rates[$coin] = $request->minutes;
        $esp->rates = json_encode($rates);
        $esp->save();

        return back()->with('success', "Rate ₱{$coin} updated!");
    }

    // Delete a rate
    public function deleteRate($espId, $coin)
    {
        $esp = Esp8266::where('user_id', Auth::id())
            ->findOrFail($espId);

        $rates = json_decode($esp->rates ?? '{}', true);

        if (isset($rates[$coin])) {
            unset($rates[$coin]);
            $esp->rates = json_encode($rates);
            $esp->save();
            return back()->with('success', "Rate ₱{$coin} deleted!");
        }

        return back()->with('error', 'Rate not found.');
    }
}
