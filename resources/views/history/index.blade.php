@extends('layouts.app')

@section('title', 'History')

@push('styles')
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.css" rel="stylesheet">
@endpush

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">History</span>
    </div>

    <div class="mb-4 flex flex-col md:flex-row md:items-end md:space-x-3 space-y-2 md:space-y-0">
        <div>
            <label for="start_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                Start Date & Time
            </label>
            <input type="datetime-local" id="start_datetime" 
                class="mt-1 w-full md:w-60 px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm
                    focus:outline-none focus:ring focus:ring-green-500 focus:border-green-500"
                value="{{ date('Y-m-d') }}T10:00">
        </div>

        <div>
            <label for="end_datetime" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                End Date & Time
            </label>
            <input type="datetime-local" id="end_datetime" 
                class="mt-1 w-full md:w-60 px-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm
                    focus:outline-none focus:ring focus:ring-green-500 focus:border-green-500"
                value="{{ date('Y-m-d') }}T22:00">
        </div>

        <div>
            <button id="filter-btn" 
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-semibold">
                Filter
            </button>
        </div>

        <div>
            <button id="export-pdf" 
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-semibold">
                Export PDF
            </button>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div class="md:h-24 bg-white dark:bg-gray-700 rounded-md flex items-center px-4 gap-4">
            <div class="md:h-12 md:w-12 bg-green-500 flex items-center justify-center rounded-md">
                <i class="fa-solid fa-qrcode text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-semibold text-green-500">Total QRIS</span>
                <span class="font-semibold dark:text-gray-200 qris-total">Rp 0 (0 transaction)</span>
            </div>
        </div>

        <div class="md:h-24 bg-white dark:bg-gray-700 rounded-md flex items-center px-4 gap-4">
            <div class="md:h-12 md:w-12 bg-green-500 flex items-center justify-center rounded-md">
                <i class="fa-solid fa-money-bill-wave text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-semibold text-green-500">Total TUNAI</span>
                <span class="font-semibold dark:text-gray-200 tunai-total">Rp 0 (0 transaction)</span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md overflow-x-auto max-w-full h-full">
        <table id="table-history" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Payment</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script type="module" src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script type="module" src="https://cdn.datatables.net/2.3.4/js/dataTables.tailwindcss.js"></script>

    <script type="module">
        $(document).ready(function() {
            let table = $('#table-history').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                order: [[7, 'desc']],
                ajax: {
                    url: "{{ route('history') }}",
                    data: function (d) {
                        d.start_datetime = $('#start_datetime').val();
                        d.end_datetime   = $('#end_datetime').val();
                    },
                    dataSrc: function (json) {
                        $('.qris-total').text(
                            new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(json.qris_total)
                            + ` (${json.qris_count} transaction)`
                        );
                        $('.tunai-total').text(
                            new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(json.tunai_total)
                            + ` (${json.tunai_count} transaction)`
                        );

                        return json.data;
                    }
                },
                order: [[1, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'type', name: 'type', render: function (data) {
                        let typeClass = data === 'food' 
                            ? 'text-indigo-600 dark:text-indigo-300' 
                            : 'text-purple-600 dark:text-purple-300';
                        return `<span class="inline-block px-2 py-1 text-xs font-bold tracking-wider uppercase rounded-lg ${typeClass}">${data ?? ''}</span>`;
                    }},
                    { data: 'quantity', name: 'quantity' },
                    { data: 'payment_method', name: 'payment_method', render: function (data) {
                        let paymentClass = data === 'tunai' 
                            ? 'text-blue-600 dark:text-blue-300' 
                            : 'text-red-600 dark:text-red-300';
                        return `<span class="inline-block px-2 py-1 text-xs font-bold tracking-wider uppercase rounded-lg ${paymentClass}">${data ?? ''}</span>`;
                    }},
                    { data: 'price', name: 'price', render: (data, type) =>
                        (type === 'display' || type === 'filter') 
                            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data) 
                            : data
                    },
                    { data: 'total_price', name: 'total_price', render: (data, type) =>
                        (type === 'display' || type === 'filter') 
                            ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data) 
                            : data
                    },
                    { 
                        data: 'created_at', 
                        name: 'created_at',
                        render: function (data, type) {
                            if (type === 'display' || type === 'filter') {
                                let date = new Date(data);
                                return date.toLocaleString('id-ID', {
                                    day: '2-digit',
                                    month: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });
                            }
                            return data;
                        }
                    }
                ]
            });

            // tombol filter
            $('#filter-btn').on('click', function() {
                table.ajax.reload();
            });

            $('#export-pdf').on('click', function() {
                let start = $('#start_datetime').val();
                let end   = $('#end_datetime').val();

                $.ajax({
                    url: "{{ route('history.export') }}",
                    type: "POST",
                    data: {
                        start_date: start,
                        end_date: end,
                        _token: "{{ csrf_token() }}"
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (data) {
                        let blob = new Blob([data], { type: "application/pdf" });
                        let url = window.URL.createObjectURL(blob);
                        window.open(url, "_blank");
                    },
                    error: function(xhr) {
                        Swal.fire("Error", "Gagal export PDF", "error");
                    }
                });
            });

        });
    </script>
@endpush