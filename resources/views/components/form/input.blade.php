@props([
    'type' => 'text',
    'id' => '',
    'name' => '',
    'placeholder' => '',
    'value' => null,
    'autofocus' => false,
    'class' => ''
])

<input
    type="{{ $type }}" 
    id="{{ $id }}" 
    name="{{ $name }}" 
    placeholder="{{ $placeholder }}" 
    value="{{ $value }}"
    @if ($autofocus) autofocus @endif
    autocomplete="off"
    class="{{ $class }} px-4 py-2 rounded-md outline-none border border-gray-300 w-full focus:border-green-500 transition-all duration-300 text-black dark:text-gray-300 dark:placeholder:text-gray-300 bg-white dark:bg-gray-800 dark:border-gray-700">