@props(['name' => '', 'value' => '', 'label' => '', 'checked' => false])

<label class="flex items-center space-x-2 cursor-pointer">
    <input
        type="radio" 
        name="{{ $name }}" 
        id="radio-{{ $name }}-{{ $value }}"
        value="{{ $value }}"
        @checked($checked)
        class="w-4 h-4">
    <span class="dark:text-gray-200">{{ $label }}</span>
</label>