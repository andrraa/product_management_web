<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Report Management')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    @vite('resources/css/app.css')
    
    @stack('styles')
</head>
<body class="h-dvh bg-gray-200 dark:bg-gray-800 overflow-hidden transition-colors duration-300">
    <div class="flex h-dvh w-full">
        @include('partials.sidebar')

        <div class="flex flex-col flex-1">
            @include('partials.navbar')

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @vite(['resources/js/app.js', 'resources/js/theme.js'])

    @stack('scripts')
</body>
</html>