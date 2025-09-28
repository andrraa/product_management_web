@extends('layouts.app')

@section('title', 'Overview')

@push('styles')
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.css" rel="stylesheet">

    <style>
        .swal2-radio label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.6rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .swal2-radio label:hover {
            background-color: #f3f4f6;
        }

        .swal2-radio input[type="radio"] {
            accent-color: #16a34a;
        }
    </style>
@endpush

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">Dashboard</span>
    </div>

    <div class="mb-4 grid md:grid-cols-3 gap-4 h-[calc(100vh-11.3rem)]">
        {{-- PRODUCT --}}
        <div class="col-span-2 bg-white dark:bg-gray-700 rounded-md p-4 flex flex-col h-full overflow-y-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <!-- Chips -->
                <div class="flex flex-wrap gap-2">
                    <button class="chip px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white"
                        data-type="">
                        All
                    </button>
                    <button
                        class="chip px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        data-type="food">
                        Food
                    </button>
                    <button
                        class="chip px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                        data-type="billing">
                        Billing
                    </button>
                </div>

                <!-- Search -->
                <div class="relative w-full md:w-56">
                    <input type="text" id="search-input" placeholder="Search..."
                        class="w-full pl-8 pr-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 
                                            bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                                            focus:outline-none focus:ring focus:ring-green-500 focus:border-green-500 text-xs">
                    <svg class="w-4 h-4 absolute left-2 top-1/2 -translate-y-1/2 text-gray-400" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 10.5a7.5 7.5 0 0013.15 6.15z" />
                    </svg>
                </div>
            </div>

            <!-- Product Card -->
            <div id="product-list" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            </div>
        </div>

        {{-- CASHIER --}}
        <div id="cashier" class="bg-white dark:bg-gray-700 rounded-md p-4 flex flex-col h-full">
            <!-- Cart Items -->
            <div id="cart-items" class="flex-1 overflow-y-auto space-y-2 max-h-72 pr-1">
            </div>

            <!-- Footer -->
            <div class="mt-auto border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700 dark:text-gray-200">Total:</span>
                    <span id="cart-total" class="font-semibold text-gray-900 dark:text-gray-100">Rp 0</span>
                </div>

                <button id="checkout-btn"
                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md cursor-pointer">
                    Checkout
                </button>

                <button id="booking-btn"
                    class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-md cursor-pointer">
                    Booking
                </button>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">Booking</span>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md overflow-x-auto max-w-full">
        <div class="bg-white dark:bg-gray-700 p-4 rounded-md overflow-x-auto max-w-full h-full">
            <table id="table-booking" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>By</th>
                        <th>Date</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
    <script type="module" src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script type="module" src="https://cdn.datatables.net/2.3.4/js/dataTables.tailwindcss.js"></script>

    <script type="module">
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        $(document).ready(function() {
            let currentType = '';
            let currentSearch = '';
            let cart = [];

            function fetchProducts() {
                $.ajax({
                    url: '{{ route('dashboard') }}',
                    data: {
                        type: currentType,
                        search: currentSearch
                    },
                    success: function(products) {
                        let html = '';
                        products.forEach(product => {
                            html += `
                                                <div class="p-3 bg-white dark:bg-gray-800 rounded-md shadow-sm flex flex-col justify-between shrink-0">
                                                    <div>
                                                        <h3 class="font-medium text-sm text-gray-800 dark:text-gray-100 capitalize">${product.name}</h3>
                                                        <p class="text-xs text-gray-500 dark:text-gray-300 mt-0.5">
                                                            Stock: <span class="font-semibold">${product.type === 'food' ? (product.stock ?? 0) + ' pcs' : '-'}</span>
                                                        </p>
                                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold 
                                                            ${product.type === 'food'
                                    ? 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200'
                                    : 'bg-purple-100 text-purple-700 dark:bg-purple-800 dark:text-purple-200'}">
                                                            ${product.type.toUpperCase()}
                                                        </span>
                                                    </div>
                                                    <button 
                                                        class="btn-add-cart mt-2 w-full flex items-center justify-center gap-1 px-2 py-1.5 
                                                        bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md cursor-pointer"
                                                        data-id="${product.product_id}"
                                                        data-name="${product.name}"
                                                        data-price="${product.price}"
                                                        data-type="${product.type}">
                                                        <i class="fa-solid fa-cart-shopping"></i>
                                                        Keranjang
                                                    </button>
                                                </div>`;
                        });

                        $('#product-list')
                            .html(html ||
                                '<p class="col-span-full text-center text-gray-500">No products found.</p>'
                            );
                    }
                });
            }

            function renderCart() {
                let html = '';
                let total = 0;

                cart.forEach((item, index) => {
                    let subtotal = item.price * item.quantity;
                    total += subtotal;

                    html += `
                                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 p-2 rounded-md">
                                            <div>
                                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">${item.name}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-300">Rp ${item.price.toLocaleString()}</p>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button class="btn-qty px-2 py-1 text-sm bg-gray-200 dark:bg-gray-600 rounded-md dark:text-gray-200" data-index="${index}" data-action="dec">-</button>
                                                <span class="w-6 text-center text-sm dark:text-gray-200">${item.quantity}</span>
                                                <button class="btn-qty px-2 py-1 text-sm bg-green-600 text-white rounded-md" data-index="${index}" data-action="inc">+</button>
                                            </div>
                                        </div>`;
                });

                $('#cart-items').html(html || '<p class="text-sm text-gray-500">Keranjang kosong.</p>');
                $('#cart-total').text(`Rp ${total.toLocaleString()}`);
            }

            fetchProducts();

            $(document).on('click', '.chip', function() {
                $('.chip').removeClass('bg-green-600 text-white')
                    .addClass('bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300');
                $(this).removeClass('bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300')
                    .addClass('bg-green-600 text-white');

                currentType = $(this).data('type');
                fetchProducts();
            });

            $('#search-input').on('keyup', debounce(function() {
                currentSearch = $(this).val();
                fetchProducts();
            }, 300));

            $(document).on('click', '.btn-add-cart', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let price = parseInt($(this).data('price'));
                let type = $(this).data('type');

                let existing = cart.find(item => item.id === id);
                if (existing) {
                    existing.quantity++;
                } else {
                    cart.push({
                        id,
                        name,
                        price,
                        type,
                        quantity: 1
                    });
                }
                renderCart();
            });

            $(document).on('click', '.btn-qty', function() {
                let index = $(this).data('index');
                let action = $(this).data('action');

                if (action === 'inc') {
                    cart[index].quantity++;
                } else if (action === 'dec') {
                    cart[index].quantity--;
                    if (cart[index].quantity <= 0) cart.splice(index, 1);
                }
                renderCart();
            });

            $('#checkout-btn').on('click', function() {
                let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

                if (cart.length < 1) {
                    Swal.fire("Oops!", "Keranjang kosong.", "error");
                    return;
                }

                Swal.fire({
                    title: "Pilih Metode Pembayaran",
                    input: 'radio',
                    inputOptions: {
                        tunai: 'Tunai',
                        qris: 'QRIS'
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Pilih salah satu metode pembayaran!';
                        }
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Lanjutkan',
                    cancelButtonText: 'Batal',
                    preConfirm: (payment) => {
                        return payment;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let payment = result.value;

                        $.ajax({
                            url: "{{ route('checkout') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                cart: cart,
                                payment: payment,
                            },
                            success: function(res) {
                                Swal.fire("Success", "Checkout Success.", "success");
                                cart = [];
                                renderCart();
                                fetchProducts();
                            },
                            error: function() {
                                Swal.fire("Failed", "Failed to checkout.", "error");
                            }
                        });
                    }
                });
            });

            $('#booking-btn').on('click', function() {
                const hasFood = cart.some(item => item.type === 'food');

                if (cart.length < 1) {
                    Swal.fire("Oops!", "Keranjang kosong.", "error");
                    return;
                }

                if (hasFood) {
                    Swal.fire("Oops!", "Cart tidak boleh mengandung item dengan tipe 'food'.", "error");
                    return;
                }

                Swal.fire({
                    title: 'Form Booking',
                    html: `
                        <div class="text-left">
                            <label for="cust_name" class="block text-sm font-medium mb-1">Nama</label>
                            <input id="cust_name" class="w-full rounded-md px-4 py-2 outline-none border border-gray-300 dark:border-gray-200/20 text-sm" placeholder="Masukkan nama pelanggan">

                            <label for="cust_phone" class="block text-sm font-medium mt-3 mb-1">Nomor Telepon</label>
                            <input id="cust_phone" class="w-full rounded-md px-4 py-2 outline-none border border-gray-300 dark:border-gray-200/20 text-sm" placeholder="08xxxxxxxxxx">

                            <label for="cust_notes" class="block text-sm font-medium mt-3 mb-1">Catatan</label>
                            <input id="cust_notes" class="w-full rounded-md px-4 py-2 outline-none border border-gray-300 dark:border-gray-200/20 text-sm" placeholder="Catatan">

                            <label for="payment_method" class="block text-sm font-medium mt-3 mb-1">Metode Pembayaran</label>
                            <select id="payment_method" class="w-full rounded-md px-4 py-2 outline-none border border-gray-300 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 text-sm">
                                <option value="">-- Pilih Metode --</option>
                                <option value="tunai">Tunai</option>
                                <option value="qris">QRIS</option>
                            </select>

                            <label for="payment_status" class="block text-sm font-medium mt-3 mb-1">Status Pembayaran</label>
                            <select id="payment_status" class="w-full rounded-md px-4 py-2 outline-none border border-gray-300 bg-white text-gray-900 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 text-sm">
                                <option value="">-- Pilih Status --</option>
                                <option value="1">Lunas</option>
                                <option value="0">Belum Lunas</option>
                            </select>
                        </div>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Booking',
                    cancelButtonText: 'Cancel',
                    ...getSwalTheme(),
                    preConfirm: () => {
                        const name = $('#cust_name').val().trim();
                        const phone = $('#cust_phone').val().trim();
                        const notes = $('#cust_notes').val().trim();
                        const payment_method = $('#payment_method').val();
                        const payment_status = $('#payment_status').val();

                        if (!name || !payment_method || !payment_status) {
                            Swal.showValidationMessage('Semua field wajib diisi');
                            return false;
                        }

                        return {
                            name,
                            phone,
                            notes,
                            payment_method,
                            payment_status
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = result.value;

                        Swal.fire({
                            title: 'Memproses booking...',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();

                                $.ajax({
                                    url: "{{ route('booking.store') }}",
                                    method: "POST",
                                    data: {
                                        name: data.name,
                                        phone: data.phone,
                                        notes: data.notes,
                                        payment_method: data.payment_method,
                                        payment_status: data.payment_status,
                                        cart: cart
                                    },
                                    success: function(res) {
                                        Swal.fire("Success",
                                            "Booking berhasil dibuat!",
                                            "success");
                                        cart = [];
                                        renderCart();
                                        $('#table-booking').DataTable().ajax
                                            .reload();
                                    },
                                    error: function() {
                                        Swal.fire("Error",
                                            "Gagal melakukan booking.",
                                            "error");
                                    }
                                });
                            }
                        });
                    }
                });
            });

            $('#table-booking').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: "{{ route('booking.index') }}",
                order: [
                    [6, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        render: function(data, type, row) {
                            const phone = row.customer_phone ?? '-';

                            return `
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 capitalize">${row.customer_name}</div>
                                    <div class="text-gray-500 dark:text-gray-300 text-sm">Phone: ${phone}</div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'package_name',
                        name: 'package_name',
                        render: function(data, type, row) {
                            return `
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 capitalize">${row.package_name}</div>
                                    <div class="text-gray-500 dark:text-gray-300 text-sm">Price: ${formatRupiah(row.package_price)}</div>
                                    <div class="text-gray-500 dark:text-gray-300 text-sm">Quantity: ${row.package_quantity}</div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'total',
                        name: 'total',
                        render: function(data, type, row) {
                            return `
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 capitalize">${formatRupiah(data)}</div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method',
                        render: function(data, type, row) {
                            const status = row.is_paid === 1 ? 'PAID' : 'UNPAID';
                            const statusClass = data === 1 ?
                                'bg-green-500/10 text-green-700 dark:text-green-300' :
                                'bg-red-500/10 text-red-700 dark:text-red-300';

                            const methodClass = row.payment_method === 'tunai' ?
                                'bg-yellow-400/10 text-yellow-700 dark:text-yellow-300' :
                                'bg-blue-500/10 text-blue-700 dark:text-blue-300';

                            return `
                                <div class="text-sm space-y-1">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        Method: 
                                        <span class="uppercase text-xs font-bold px-2 py-0.5 rounded-md ${methodClass}">
                                            ${row.payment_method}
                                        </span>
                                    </div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        Status: 
                                        <span class="uppercase text-xs font-bold px-2 py-0.5 rounded-md ${statusClass}">
                                            ${status}
                                        </span>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'by_user_name',
                        name: 'by_user_name',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type) {
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
                    },
                    {
                        data: 'notes',
                        name: 'notes',
                        render: function(data, type, row) {
                            return `
                                <div class="text-sm text-gray-800 dark:text-gray-200 whitespace-normal break-words max-w-[250px]">
                                    ${data || '-'}
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button 
                                    class="btn-complete cursor-pointer" 
                                    data-url="${data.edit}" 
                                    title="Tandai Selesai">
                                    <i class="fa-solid fa-check text-xs text-green-600 dark:text-green-400"></i>
                                </button>
                                <button 
                                    type="button" class="cursor-pointer button-delete" onclick="confirmDelete('${data.delete}', this.closest('tr'))">
                                    <i class="fa-solid fa-trash text-xs text-red-600 dark:text-red-400"></i>
                                </button>
                            `;
                        }
                    }
                ]
            });

            function getSwalTheme() {
                const isDark = document.documentElement.classList.contains('dark');

                return {
                    background: isDark ? '#1f2937' : '#fff',
                    color: isDark ? '#e5e7eb' : '#111827',
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280'
                };
            }

            function formatRupiah(angka) {
                return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
        });

        $(document).on('click', '.btn-complete', function(e) {
            e.preventDefault();

            const $btn = $(this);
            const url = $btn.data('url');

            Swal.fire({
                title: 'Tandai sebagai selesai?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesai',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: url,
                        method: 'PUT',
                    }).then(response => {
                        if (!response.success) {
                            throw new Error('Gagal menyelesaikan booking');
                        }
                        return response;
                    }).catch(err => {
                        Swal.showValidationMessage(err.message);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Selesai", "Booking telah ditandai sebagai selesai.", "success");
                    $('#table-booking').DataTable().ajax.reload(null, false);
                }
            });
        });
    </script>
@endpush
