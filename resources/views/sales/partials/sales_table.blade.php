<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
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
                    <td>{{ $loop->iteration + ($sales->currentPage() - 1) * $sales->perPage() }}</td>
                    <td>{{ $sale->voucher }}</td>
                    <td>{{ $sale->minutes }}</td>
                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No sales found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        {{ $sales->links() }}
    </div>
</div>
