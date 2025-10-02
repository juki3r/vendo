<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Esp8266;
use App\Models\ActiveClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $esps = Esp8266::where('user_id', Auth::id())->get();
        return view('esp8266s.index', compact('esps'));
    }

    public function storeRate(Request $request, Esp8266 $esp)
    {
        $data = $request->validate([
            'coin'    => 'required|numeric|min:1',
            'minutes' => 'required|numeric|min:1',
        ]);

        // Now $esp->rates is already an array
        $rates = $esp->rates ?? [];
        $rates[$data['coin']] = $data['minutes'];

        $esp->rates = $rates;
        $esp->save();

        return back()->with('success', 'Rate added successfully!');
    }

    public function updateRate(Request $request, Esp8266 $esp, $coin)
    {
        $data = $request->validate([
            'minutes' => 'required|numeric|min:1',
        ]);

        $rates = $esp->rates ?? [];
        if (isset($rates[$coin])) {
            $rates[$coin] = $data['minutes'];
            $esp->rates = $rates;
            $esp->save();
        }

        return back()->with('success', "Rate ₱$coin updated!");
    }

    public function deleteRate(Esp8266 $esp, $coin)
    {
        $rates = $esp->rates ?? [];
        unset($rates[$coin]);

        $esp->rates = $rates;
        $esp->save();

        return back()->with('success', "Rate ₱$coin deleted!");
    }

    //View all sales
    public function viewSalesHistory(Request $request)
    {
        $query = Sales::where('user_id', Auth::id())->orderBy('created_at', 'desc');

        $history = $query->paginate(10);

        return view('history.index', compact('history'));
    }

    //View all active Clients
    public function indexActiveClient(Request $request)
    {
        $deviceId = $request->get('device_id'); // get device_id from query string or route

        $clients = ActiveClient::where('user_id', auth()->id())
            ->where('device_id', $deviceId)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('active_clients.index', compact('clients', 'deviceId'));
    }
}
