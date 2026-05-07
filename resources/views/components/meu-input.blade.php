@props([
    'name',
    'label',
    'type' => 'text',
    'as' => 'input',
])

@php
    $id = $attributes->get('id') ?? str_replace(['[', ']'], '_', $name);
    $controlClass = 'w-full p-2 mb-4 rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white';
    $extra = $attributes->except('id');
@endphp

<label class="block mb-1 text-gray-700 dark:text-gray-300" for="{{ $id }}">{{ $label }}</label>

@if ($as === 'textarea')
    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $extra->merge(['class' => $controlClass]) }}
    >{{ $slot }}</textarea>
@elseif ($as === 'select')
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        {{ $extra->merge(['class' => $controlClass]) }}
    >
        {{ $slot }}
    </select>
@else
    <input
        name="{{ $name }}"
        type="{{ $type }}"
        id="{{ $id }}"
        {{ $extra->merge(['class' => $controlClass]) }}
    />
@endif
