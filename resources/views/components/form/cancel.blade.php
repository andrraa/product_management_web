@props([
    'route' => '', 
    'label' => 'Cancel'
])

<a 
    type="button"
    href="{{ $route }}"
    class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-800/50 dark:hover:bg-gray-800 transition-colors duration-300">
    {{ $label }}
</a>