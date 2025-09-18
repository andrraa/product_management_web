@extends('layouts.auth')

@section('content')
    <div class="bg-white dark:bg-gray-700 rounded-md p-4 md:w-[380px] w-full border border-gray-300 dark:border-none transition-colors duration-300">
        <div class="mb-4">
            <h1 class="font-bold text-xl tracking-wide dark:text-white">Login</h1>
            <h2 class="font medium text-sm tracking-wide text-gray-500 dark:text-gray-300">
                Enter your account information to access the system.
            </h2>
        </div>

        <form id="form-login" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-2">
                <x-form.label 
                    for="username" 
                    label="username" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    id="username" 
                    name="username" 
                    placeholder="Enter your username" 
                    :autofocus="true" />
            </div>

            <div class="mb-6">
                <x-form.label
                    for="password" 
                    label="password" 
                    :required="true"
                    class="block" />
                
                <x-form.input
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter your password" />
            </div>

            <x-form.submit id="button-login" label="login" class="w-full" />
        </form>
    </div>

    <footer class="mt-2">
        <span class="text-sm tracking-wide font-medium text-gray-500 dark:text-gray-300">
            Copyright 2025. Report Management System
        </span>
    </footer>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('vendor/jsvalidation/jsvalidation.js') }}"></script>
    <script type="module">
        {!! $validator !!}
    </script>
@endpush
