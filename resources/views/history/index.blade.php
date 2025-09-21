@extends('layouts.app')

@section('title', 'History')

@push('styles')
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.css" rel="stylesheet">
@endpush

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">History</span>
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
            $('#table-history').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('history') }}",
                order: [[1, 'desc']],
                columns: [
                    {
                        data: 'DT_RowIndex', 
                        name: 'DT_RowIndex', 
                        orderable: false, 
                        searchable: false
                    },
                    {
                        data: 'name', 
                        name: 'name'
                    },
                    {
                        data: 'type', 
                        name: 'type',
                        render: function (data, type, row) {
                            let typeClass = '';

                            if (data === 'food') {
                                typeClass = 'text-blue-600 dark:text-blue-300';
                            } else if (data === 'billing') {
                                typeClass = 'text-red-600 dark:text-red-300';
                            }

                            return `
                                <span class="inline-block px-2 py-1 text-xs font-bold tracking-wider uppercase rounded-lg ${typeClass}">
                                    ${data ?? ''}
                                </span>
                            `;
                        }
                    },
                    {
                        data: 'quantity', 
                        name: 'quantity'
                    },
                    {
                        data: 'payment_method', 
                        name: 'payment_method',
                        render: function (data, type, row) {
                            let paymentClass = '';

                            if (data === 'tunai') {
                                paymentClass = 'text-blue-600 dark:text-blue-300';
                            } else if (data === 'qriss') {
                                paymentClass = 'text-red-600 dark:text-red-300';
                            }

                            return `
                                <span class="inline-block px-2 py-1 text-xs font-bold tracking-wider uppercase rounded-lg ${paymentClass}">
                                    ${data ?? ''}
                                </span>
                            `;
                        }
                    },
                    {
                        data: 'price', 
                        name: 'price',
                        render: function (data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(data);
                            }
                            return data;
                        }
                    },
                    {
                        data: 'total_price', 
                        name: 'total_price',
                        render: function (data, type, row) {
                            if (type === 'display' || type === 'filter') {
                                return new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    minimumFractionDigits: 0
                                }).format(data);
                            }
                            return data;
                        }
                    },
                ]
            });
        });
    </script>
@endpush