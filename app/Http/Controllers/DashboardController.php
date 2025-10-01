<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Esp8266;
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
    public function viewSales(Request $request)
    {
        $query = Sale::where('user_id', Auth::id());

        // Search by voucher
        if ($request->has('search') && $request->search != '') {
            $query->where('voucher', 'like', '%' . $request->search . '%');
        }

        // Paginate 10 per page
        $sales = $query->orderBy('created_at', 'desc')->paginate(10);

        // Keep the search query in pagination links
        $sales->appends($request->only('search'));

        return view('sales.index', compact('sales'));
    }
}
