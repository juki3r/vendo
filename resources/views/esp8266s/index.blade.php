<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('WiFi Rates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @forelse($esps as $esp)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">
                            {{ $esp->name ?? 'ESP8266 #'.$esp->id }}
                        </h3>

                        {{-- Add Rate Form --}}
                        <form action="{{ route('esp8266s.storeRate', $esp->id) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium">Coin (₱)</label>
                                    <input type="number" name="coin" step="1" min="1"
                                           class="mt-1 w-full border rounded p-2" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium">Time (minutes)</label>
                                    <input type="number" name="minutes" min="1"
                                           class="mt-1 w-full border rounded p-2" required>
                                </div>
                                <div class="flex items-end">
                                    <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                        Add Rate
                                    </button>
                                </div>
                            </div>
                        </form>

                        @php
                            $rates = json_decode($esp->rates ?? '{}', true);
                        @endphp

                        @if(empty($rates))
                            <p class="text-gray-600">No rates found. Add a rate above.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300 divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left border">Coin (₱)</th>
                                            <th class="px-4 py-2 text-left border">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($rates as $coin => $minutes)
                                            <tr>
                                                <td class="px-4 py-2 border font-semibold">₱{{ $coin }}</td>
                                                <td class="px-4 py-2 border">
                                                    @php
                                                        $mins = (int) $minutes;
                                                        if ($mins >= 60) {
                                                            $days = floor($mins / 1440);
                                                            $hours = floor(($mins % 1440) / 60);
                                                            $remMins = $mins % 60;

                                                            $display = '';
                                                            if ($days > 0) $display .= $days.' day'.($days>1?'s ':' ');
                                                            if ($hours > 0) $display .= $hours.' hour'.($hours>1?'s ':' ');
                                                            if ($remMins > 0) $display .= $remMins.' minute'.($remMins>1?'s':'');
                                                        } else {
                                                            $display = $mins.' minute'.($mins>1?'s':'');
                                                        }
                                                    @endphp
                                                    {{ $display }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center text-gray-600">
                        You have no ESP8266 devices.
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
