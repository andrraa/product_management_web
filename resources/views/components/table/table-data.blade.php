@props(['value' => ''])

<td class="px-4 py-2 dark:text-gray-200 text-sm whitespace-nowrap">
    @if ($value !== '')
        {{ $value }}
    @else
        {{ $slot }}
    @endif
</td>
