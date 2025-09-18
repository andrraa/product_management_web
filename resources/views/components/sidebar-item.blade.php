@props(['pattern' => '', 'icon' => '', 'label' => '', 'route' => '', 'variant' => 'normal'])

@php
    $isActive = request()->routeIs($pattern);
@endphp

@if ($variant === 'logout')
    <button type="submit" 
        class="group w-full text-left h-12 border-r-4 flex items-center px-4 space-x-4 border-r-transparent text-gray-400 hover:bg-red-100 dark:hover:bg-gray-800 cursor-pointer transition-colors duration-300">
        <div class="h-6 w-6 rounded-md flex items-center justify-center bg-gray-200 group-hover:bg-red-500 transition-colors duration-300">
            <i class="fa-solid fa-arrow-right-from-bracket text-[10px] text-gray-500 group-hover:text-white transition-colors duration-300"></i>
        </div>
        <span class="font-medium tracking-wide text-[13px] group-hover:text-red-600 group-hover:dark:text-white">
            Logout
        </span>
    </button>
@else
    <a href="{{ $route }}" class="block">
        <div class="h-12 border-r-4 flex items-center px-4 space-x-4 {{ $isActive ? 'bg-green-500/20 border-r-green-500 text-green-600 dark:text-gray-200' : 'border-r-transparent text-gray-400 hover:bg-gray-100 hover:dark:bg-gray-800' }}">
            <div class="h-6 w-6 rounded-md flex items-center justify-center {{ $isActive ? 'bg-green-500' : 'bg-gray-200' }}">
                <i class="fa-solid {{ $icon }} text-[10px] {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
            </div>

            <span class="font-medium tracking-wide text-[13px]">{{ $label }}</span>
        </div>
    </a>
@endif