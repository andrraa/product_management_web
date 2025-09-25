<nav class="h-16 flex items-center justify-between px-4 bg-white dark:bg-gray-700 transition-colors duration-300 border-b border-b-gray-300 dark:border-b-gray-900 shrink-0">
    <x-theme-toggle variant="small" />

    <div class="flex items-center shrink-0 space-x-2">
        <div class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-user text-white text-[12px]"></i>
        </div>

        <div class="flex flex-col">
            <span class="font-medium text-green-600 dark:text-gray-200 text-sm tracking-wide md:block hidden">
                {{ $authUser->name }}
            </span>
            <span class="font-medium text-green-600 dark:text-gray-200 text-[10px] tracking-wide md:block hidden capitalize">
                @if ($authUser->shift)
                    Shift: {{ $authUser->shift }}
                @else
                    Shift: -
                @endif
            </span>
        </div>
    </div>
</nav>