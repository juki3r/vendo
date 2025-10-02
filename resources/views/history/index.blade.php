<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Voucher</th>
                                <th>Minutes</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $sale)
                                <tr>
                                    <td>{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                                    <td>{{ $sale->voucher }}</td>
                                    <td>{{ $sale->minutes }}</td>
                                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Bootstrap Pagination -->
                {{-- <div class="mt-3">
                    {{ $sales->links() }}
                </div> --}}
                @if ($sales->hasPages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">

                            {{-- Previous Page Link --}}
                            <li class="page-item {{ $sales->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $sales->previousPageUrl() }}">Previous</a>
                            </li>

                            {{-- Pagination Elements --}}
                            @foreach ($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
                                <li class="page-item {{ $sales->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            {{-- Next Page Link --}}
                            <li class="page-item {{ $sales->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $sales->nextPageUrl() }}">Next</a>
                            </li>

                        </ul>
                    </nav>
                    @endif

            </div>
        </div>
    </div>
</div>

</x-app-layout>
