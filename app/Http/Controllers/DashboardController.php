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
