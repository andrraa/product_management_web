@extends('layouts.app')

@section('title', 'Overview')

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">Dashboard</span>
    </div>
    
    <div class="grid md:grid-cols-3 gap-4 h-[calc(100vh-11.3rem)]"> 
        {{-- PRODUCT --}}
        <div class="col-span-2 bg-white dark:bg-gray-700 rounded-md p-4 flex flex-col h-full overflow-y-auto">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <!-- Chips -->
                <div class="flex flex-wrap gap-2">
                    <button class="chip px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600 text-white" data-type="">
                        All
                    </button>
                    <button class="chip px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300" data-type="food">
                        Food
                    </button>
                    <button class="chip px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300" data-type="billing">
                        Billing
                    </button>
                </div>

                <!-- Search -->
                <div class="relative w-full md:w-56">
                    <input type="text" id="search-input" placeholder="Search..."
                        class="w-full pl-8 pr-2 py-1.5 rounded-md border border-gray-300 dark:border-gray-600 
                            bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                            focus:outline-none focus:ring focus:ring-green-500 focus:border-green-500 text-xs">
                    <svg class="w-4 h-4 absolute left-2 top-1/2 -translate-y-1/2 text-gray-400" 
                        fill="none" stroke="currentColor" stroke-width="2" 
                        viewBox="0 0 24 24">
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
            <div id="cart-items" class="flex-1 overflow-y-auto space-y-2 max-h-56 pr-1">
            </div>

            <div class="mt-4 border-t border-gray-200 dark:border-gray-600 pt-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700 dark:text-gray-200">Total:</span>
                    <span id="cart-total" class="font-semibold text-gray-900 dark:text-gray-100">Rp 0</span>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Uang Diterima</label>
                    <input type="number" id="uang-diterima" placeholder="0"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 
                            bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm
                            focus:outline-none focus:ring focus:ring-green-500 focus:border-green-500">
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-700 dark:text-gray-200">Kembalian:</span>
                    <span id="cart-change" class="font-semibold text-gray-900 dark:text-gray-100">Rp 0</span>
                </div>

                <div>
                    <span class="block text-sm text-gray-700 dark:text-gray-200 mb-1">Metode Pembayaran</span>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-1 text-sm cursor-pointer">
                            <input checked type="radio" name="payment" value="tunai" class="text-green-600 focus:ring-green-500">
                            <span class="text-gray-700 dark:text-gray-200">Tunai</span>
                        </label>
                        <label class="flex items-center gap-1 text-sm cursor-pointer">
                            <input type="radio" name="payment" value="qris" class="text-green-600 focus:ring-green-500">
                            <span class="text-gray-700 dark:text-gray-200">QRIS</span>
                        </label>
                    </div>
                </div>

                <button id="checkout-btn" 
                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md">
                    Checkout
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    <script type="module">
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        $(document).ready(function () {
            let currentType = '';
            let currentSearch = '';
            let cart = [];

            function fetchProducts() {
                $.ajax({
                    url: '{{ route('dashboard') }}',
                    data: { type: currentType, search: currentSearch },
                    success: function (products) {
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
                            .html(html || '<p class="col-span-full text-center text-gray-500">No products found.</p>');
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
                updateChange();
            }

            function updateChange() {
                let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                let received = parseInt($('#uang-diterima').val()) || 0;
                let change = received - total;

                if (change < 0) change = 0;

                $('#cart-change').text(`Rp ${change.toLocaleString('id-ID')}`);
            }

            fetchProducts();

            $(document).on('click', '.chip', function () {
                $('.chip').removeClass('bg-green-600 text-white')
                        .addClass('bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300');
                $(this).removeClass('bg-gray-200 text-gray-700 dark:bg-gray-800 dark:text-gray-300')
                    .addClass('bg-green-600 text-white');

                currentType = $(this).data('type');
                fetchProducts();
            });

            $('#search-input').on('keyup', debounce(function () {
                currentSearch = $(this).val();
                fetchProducts();
            }, 300));

            $(document).on('click', '.btn-add-cart', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let price = parseInt($(this).data('price'));
                let type = $(this).data('type');

                let existing = cart.find(item => item.id === id);
                if (existing) {
                    existing.quantity++;
                } else {
                    cart.push({ id, name, price, type, quantity: 1 });
                }
                renderCart();
            });

            $(document).on('click', '.btn-qty', function () {
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

            $('#checkout-btn').on('click', function () {
                let payment = $('input[name="payment"]:checked').val();
                let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                let uang    = parseInt($('#uang-diterima').val()) || 0;

                if (cart.length < 1) {
                    Swal.fire("Oops!", "Keranjang kosong.", "error");
                    return;
                }

                if (!payment) {
                    alert('Pilih metode pembayaran!');
                    return;
                }

                if (uang <= 0) {
                    Swal.fire("Oops!", "Masukkan jumlah uang yang diterima.", "error");
                    return;
                }

                if (uang < total) {
                    Swal.fire("Oops!", "Uang diterima kurang dari total belanja.", "error");
                    return;
                }

                $.ajax({
                    url: "{{ route('checkout') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cart: cart,
                        payment: payment,
                    },
                    success: function (res) {
                        Swal.fire("Success", "Checkout Success.", "success");
                        cart = [];
                        renderCart();
                        fetchProducts();
                        $('#uang-diterima').val(0);
                        updateChange();
                    },
                    error: function () {
                        Swal.fire("Failed", "Failed to checkout.", "error");
                    }
                });
            });

            $(document).on('input', '#uang-diterima', function () {
                updateChange();
            });
        });
    </script>
@endpush