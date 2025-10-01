<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Search Input -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="search" class="form-control" placeholder="Search by voucher...">
                </div>
            </div>

            <!-- Table container -->
            <div id="sales-table">
                @include('sales.partials.sales_table', ['sales' => $sales])
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Live search
            $('#search').on('keyup', function() {
                let query = $(this).val();
                $.ajax({
                    url: "{{ route('viewSales') }}",
                    type: "GET",
                    data: { search: query },
                    success: function(data) {
                        $('#sales-table').html(data);
                    }
                });
            });

            // Pagination links via AJAX
            $(document).on('click', '#sales-table .pagination a', function(e) {
                e.preventDefault();
                let url = $(this).attr('href');
                $.get(url, { search: $('#search').val() }, function(data) {
                    $('#sales-table').html(data);
                });
            });
        });
    </script>
    @endpush

</x-app-layout>
