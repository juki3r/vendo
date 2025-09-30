<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('WiFi Rates') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @foreach($esps as $esp)
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">
                            {{ $esp->name ?? 'ESP #'.$esp->id }}
                        </h3>

                        {{-- Add Rate Form --}}
                        <form action="{{ route('esp.rates.store', $esp->id) }}" method="POST" class="flex gap-4 mb-6">
                            @csrf
                            <input type="number" name="coin" placeholder="Coin ₱" class="border rounded p-2 w-28" required>
                            <input type="number" name="minutes" placeholder="Minutes" class="border rounded p-2 w-28" required>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
                        </form>

                        @php
                            $rates = json_decode($esp->rates ?? '{}', true);
                        @endphp

                        @if(empty($rates))
                            <p class="text-gray-500">No rates yet.</p>
                        @else
                            <table class="min-w-full border">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 border">Coin (₱)</th>
                                        <th class="px-4 py-2 border">Time</th>
                                        <th class="px-4 py-2 border">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rates as $coin => $minutes)
                                        <tr>
                                            <td class="px-4 py-2 border">₱{{ $coin }}</td>
                                            <td class="px-4 py-2 border">
                                                <form action="{{ route('esp.rates.update', [$esp->id, $coin]) }}" method="POST" class="flex gap-2">
                                                    @csrf @method('PUT')
                                                    <input type="number" name="minutes" value="{{ $minutes }}" class="border rounded p-1 w-24">
                                                    <button type="submit" class="bg-green-600 text-white px-2 rounded">Update</button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-2 border text-center">
                                                <form action="{{ route('esp.rates.delete', [$esp->id, $coin]) }}" method="POST" onsubmit="return confirm('Delete this rate?')">
                                                    @csrf @method('DELETE')
                                                    <button class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>
