@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">Profile</span>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md mb-4">
        <h1 class="tracking-wide text-green-600 dark:text-gray-200 mb-4">User Profile</h1>

        <form id="form-profile" action="{{ route('profile.store') }}" method="POST">
            @csrf

            <div class="mb-2">
                <x-form.label 
                    for="name" 
                    label="name" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    id="name" 
                    name="name"
                    value="{{ $user->name ?? null }}" />
            </div>

            <div class="mb-4">
                <x-form.label 
                    for="username" 
                    label="username" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    id="username" 
                    name="username"
                    value="{{ $user->username ?? null }}" />
            </div>

            <div class="flex items-center justify-end space-x-2 shrink-0">
                <x-form.cancel route="{{ route('profile.index') }}" />

                <x-form.submit id="submit-profile" label="Update Profile" />
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md">
        <h1 class="tracking-wide text-green-600 dark:text-gray-200 mb-4">User Password</h1>

        <form id="form-profile-password" action="{{ route('profile.password') }}" method="POST">
            @csrf

            <div class="mb-2">
                <x-form.label 
                    for="password" 
                    label="password" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    type="password"
                    id="password" 
                    name="password" 
                    placeholder="Input new password" />
            </div>

            <div class="mb-4">
                <x-form.label 
                    for="password_confirmation" 
                    label="password confirmation" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    type="password"
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Input password confirmation" />
            </div>

            <div class="flex items-center justify-end space-x-2 shrink-0">
                <x-form.cancel route="{{ route('profile.index') }}" />

                <x-form.submit id="submit-password" label="Update Password" />
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('vendor/jsvalidation/jsvalidation.js') }}"></script>
    <script type="module">
        {!! $validatorProfile !!}
        {!! $validatorPassword !!}
    </script>
@endpush