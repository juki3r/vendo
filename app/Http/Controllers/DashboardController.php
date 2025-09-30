<?php

namespace App\Http\Controllers;

use App\Models\Esp8266;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    //rates show
    public function showRates()
    {
        $esp = Esp8266::where('user_id', Auth::id())->firstOrFail();
        return view('esp8266s.index', compact('esp'));
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
