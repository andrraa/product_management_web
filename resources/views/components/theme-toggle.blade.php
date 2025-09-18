@props(['variant' => 'big'])

@if ($variant === 'big')
    <div class="fixed top-4 right-4 z-50">
        <button id="themeToggle"
            class="relative inline-flex items-center h-10 rounded-full w-20 bg-gray-300 dark:bg-gray-700 focus:outline-none transition-colors duration-300 cursor-pointer">
            <span class="sr-only">Toggle theme</span>
            <span id="toggle-circle"
                class="w-8 h-8 transform bg-white rounded-full shadow-md transition-transform duration-300 translate-x-1 dark:translate-x-11 flex items-center justify-center">
                <i id="sun-icon" class="fas fa-sun text-yellow-500 text-sm"></i>
                <i id="moon-icon" class="fas fa-moon text-blue-500 text-sm absolute opacity-0"></i>
            </span>
        </button>
    </div>
@endif

@if ($variant === 'small')
    <div class="z-50">
        <button id="themeToggle"
            class="cursor-pointer h-8 w-8 rounded-full flex items-center justify-center bg-gray-100 transition-colors duration-300 dark:bg-gray-800 hover:bg-gray-200 hover:dark:bg-gray-900">
            
            <i id="sun-icon" class="fas fa-sun text-yellow-500 text-sm"></i>
            
            <i id="moon-icon" class="fas fa-moon text-blue-500 text-sm absolute opacity-0"></i>
        </button>
    </div>
@endif
