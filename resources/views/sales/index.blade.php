<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ search: @entangle('search') }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search Input -->
            <div class="mb-6">
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Search by voucher..."
                    @input.debounce.300ms="$dispatch('search-changed', search)"
                    class="w-full sm:w-1/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"
                >
            </div>

            <!-- Table Container -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Minutes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($sales as $sale)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $sale->voucher }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $sale->minutes }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $sales->links('vendor.pagination.tailwind') }}
            </div>

        </div>
    </div>

    <!-- Alpine.js live search -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('salesTable', () => ({
                search: '',
            }));

            // Trigger form submit when search changes
            document.addEventListener('search-changed', e => {
                const params = new URLSearchParams(window.location.search);
                if(e.detail) {
                    params.set('search', e.detail);
                } else {
                    params.delete('search');
                }
                window.location.search = params.toString();
            });
        });
    </script>
</x-app-layout>
