@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">New User</span>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md">
        <form id="form-user" action="{{ route('user.store') }}" method="POST">
            @csrf

            @include('user.form')

            <div class="flex items-center justify-end space-x-2 shrink-0">
                <x-form.cancel route="{{ route('user.index') }}" />

                <x-form.submit id="submit-user" label="Save User" />
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ asset('vendor/jsvalidation/jsvalidation.js') }}"></script>
    <script type="module">
        {!! $validator !!}
    </script>
@endpush