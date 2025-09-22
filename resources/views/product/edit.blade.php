@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">Edit Product</span>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md">
        <form id="form-product" action="{{ route('product.update', $product->product_id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('product.form')

            <div class="flex items-center justify-end space-x-2 shrink-0">
                <x-form.cancel route="{{ route('product.index') }}" />

                <x-form.submit id="submit-product" label="Save Product" />
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('vendor/jsvalidation/jsvalidation.js') }}"></script>
    <script type="module">
        {!! $validator !!}

        initInputNumberFormatter();
        initInputCurrencyFormatter();
    </script>
@endpush