@props([
    'for' => '',
    'class' => '',
    'label' => '',
    'required' => false
])

<label
    for="{{ $for }}"
    class="{{ $class }} capitalize text-gray-500 dark:text-gray-300 font-medium mb-1.5 transition-colors duration-300">
    {{ $label }}
    @if ($required)
        <span class="text-red-500 dark:text-yellow-500">*</span>
    @endif
</label>