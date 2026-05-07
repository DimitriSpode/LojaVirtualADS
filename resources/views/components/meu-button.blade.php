@props([
    'color' => 'blue',
    'disabled' => false,
])

@php
    $colorClass = match ($color) {
        'red' => 'bg-red-600 hover:bg-red-700',
        'green' => 'bg-green-600 hover:bg-green-700',
        'gray' => 'bg-gray-600 hover:bg-gray-700',
        default => 'bg-blue-600 hover:bg-blue-700',
    };
    $baseClass = 'w-full p-2 rounded text-white ' . $colorClass . ($disabled ? ' opacity-50 cursor-not-allowed' : '');
@endphp

<button
    {{ $attributes->merge(['type' => 'submit', 'class' => $baseClass]) }}
    @disabled($disabled)
>
    {{ $slot }}
</button>
