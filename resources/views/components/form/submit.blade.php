@props([
    'id' => '',
    'class' => '',
    'label' => ''
])

<button 
    type="submit" 
    id="{{ $id }}"
    class="{{ $class }} capitalize px-4 py-2 rounded-md text-white bg-green-500 hover:bg-green-600 transition-colors duration-300 cursor-pointer font-semibold tracking-wide">
    {{ $label }}
</button>