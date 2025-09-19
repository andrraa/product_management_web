@extends('layouts.app')

@section('title', 'Edit Password User')

@section('content')
    <div class="mb-4">
        <span class="text-lg font-bold tracking-wide text-green-600 dark:text-gray-200">
            Edit User Password for {{ $user->name }}
        </span>
    </div>

    <div class="bg-white dark:bg-gray-700 p-4 rounded-md">
        <form id="form-user" action="{{ route('user.update.password', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-2">
                <x-form.label 
                    for="password" 
                    label="new password" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    type="password"
                    id="password" 
                    name="password" 
                    placeholder="Enter new password" />
            </div>

            <div class="mb-4">
                <x-form.label 
                    for="password_confirmation" 
                    label="new password confirmation" 
                    :required="true" 
                    class="block" />

                <x-form.input 
                    type="password"
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Enter new password confirmation" />
            </div>

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