<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        if (
        localStorage.getItem('theme') === 'dark' ||
        (!localStorage.getItem('theme') &&
            window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Login Account')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body id="body" class="h-dvh bg-gray-200 dark:bg-gray-800 flex flex-col items-center justify-center overflow-hidden max-sm:p-4 transition-colors duration-300">
    <x-theme-toggle />

    @yield('content')
    
    @vite(['resources/js/app.js', 'resources/js/theme.js'])
    
    @stack('scripts')
</body>
</html>