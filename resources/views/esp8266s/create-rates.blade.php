@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Rates for {{ $esp->name }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('esp8266s.storeRates', $esp->id) }}">
        @csrf

        <table class="table table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th>Coin Value (₱)</th>
                    <th>Minutes</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="rates-body">
                <tr>
                    <td><input type="number" name="coins[]" class="form-control" required></td>
                    <td><input type="number" name="minutes[]" class="form-control" required></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-outline-primary mb-3" id="add-rate">+ Add More</button>
        <br>
        <button type="submit" class="btn btn-success">Save Rates</button>
        <a href="{{ route('esp8266s.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('add-rate').addEventListener('click', () => {
    const tbody = document.getElementById('rates-body');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="number" name="coins[]" class="form-control" required></td>
        <td><input type="number" name="minutes[]" class="form-control" required></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-row">✕</button></td>
    `;
    tbody.appendChild(row);
});

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@endsection
