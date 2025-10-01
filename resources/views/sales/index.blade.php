<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Voucher</th>
                                <th>Minutes</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td >{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                                    <td>{{ $sale->voucher }}</td>
                                    <td >{{ $sale->minutes }}</td>
                                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                    <!-- Bootstrap Pagination -->
                    <div class="mt-3">
                        {{ $sales->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
