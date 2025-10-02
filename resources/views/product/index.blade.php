@extends('layouts.app')

@section('title', 'Product')

@push('styles')
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.css" rel="stylesheet">
@endpush

@section('content')
    <div class="grid grid-cols-2 items-center gap-2 mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">Product</span>

        <a href="{{ route('product.create')}}" type="button" class="px-4 py-2 rounded-md bg-green-500 text-white w-fit ml-auto text-sm hover:bg-green-600 transition-colors duration-300">
            Create Product
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div class="md:h-24 bg-white dark:bg-gray-700 rounded-md flex items-center px-4 gap-4">
            <div class="md:h-12 md:w-12 bg-green-500 flex items-center justify-center rounded-md">
                <i class="fa-solid fa-utensils text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-semibold dark:text-gray-200 text-green-500">Total Product Food</span>
                <span class="font-semibold dark:text-gray-200">{{ $totals['food'] }}</span>
            </div>
        </div>

        <div class="md:h-24 bg-white dark:bg-gray-700 rounded-md flex items-center px-4 gap-4">
            <div class="md:h-12 md:w-12 bg-green-500 flex items-center justify-center rounded-md">
                <i class="fa-solid fa-computer text-white"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-semibold dark:text-gray-200 text-green-500">Total Product Billing</span>
                <span class="font-semibold dark:text-gray-200">{{ $totals['billing'] }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md overflow-x-auto max-w-full h-full">
        <table id="table-product" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Actions</th>
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
            $('#table-product').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('product.index') }}",
                order: [[4, 'desc']],
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
                        data: 'stock', 
                        name: 'stock',
                        render: function (data, type, row) {
                            if (row.type === 'food') {
                                let value = data ?? 0;
                                return `${value} pcs`;
                            } else if (row.type === 'billing') {
                                return '-';
                            }
                            return data ?? '';
                        }
                    },
                    { 
                        data: 'type', 
                        name: 'type',
                        render: function (data, type, row) {
                            let typeClass = '';

                            if (data === 'food') {
                                typeClass = 'text-indigo-600 dark:text-indigo-300';
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
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                        let buttons = '';

                        if (data.add) {
                            buttons += `
                                <button type="button" data-stock-id="${data.add}" class="stock-button">
                                    <i class="fa-solid fa-plus-minus text-xs text-yellow-600 dark:text-yellow-400"></i>
                                </button>
                            `;
                        }

                        if (data.edit) {
                            buttons += `
                                <a href="${data.edit}" type="button">
                                    <i class="fa-solid fa-edit text-xs text-green-600 dark:text-green-400"></i>
                                </a>
                            `;
                        }

                        if (data.delete) {
                            buttons += `
                                <button 
                                    type="button" class="cursor-pointer button-delete" onclick="confirmDelete('${data.delete}', this.closest('tr'))">
                                    <i class="fa-solid fa-trash text-xs text-red-600 dark:text-red-400"></i>
                                </button>
                            `;
                        }

                        return buttons;
                    }
                    }
                ]
            });

            $(document).on("click", ".stock-button", function () {
                let productId = $(this).data("stock-id");

                Swal.fire({
                    title: 'Tambah Stock',
                    html: `
                        <form id="form-add-stock">
                            <input type="number" id="newStock" class="swal2-input" placeholder="Jumlah stock" min="1">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Tambah',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        let value = $('#newStock').val();
                        if (!value || value <= 0) {
                            Swal.showValidationMessage('Jumlah stock wajib diisi');
                            return false;
                        }
                        return value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('product.add.stock') }}",
                            type: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                stock_id: productId,
                                stock: result.value
                            },
                            success: function (res) {
                                if (res.success === true) {
                                    Swal.fire({
                                        icon: "success",
                                        title: "Berhasil",
                                        text: "Stock berhasil ditambahkan!"
                                    }).then(() => {
                                        $('#table-product').DataTable().ajax.reload(null, false); 
                                    });
                                } else {
                                    Swal.fire("Gagal", "Terjadi error saat update stock", "error");
                                }
                            },
                            error: function () {
                                Swal.fire("Gagal", "Tidak bisa terhubung ke server", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush