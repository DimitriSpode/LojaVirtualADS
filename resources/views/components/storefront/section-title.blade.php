@props([
    'label' => __('Lista de produtos'),
])

<div {{ $attributes->merge(['class' => 'my-12 flex justify-center']) }}>
    <div class="rounded-xl border border-gray-200/80 bg-white px-8 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-900">
        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $label }}</span>
    </div>
</div>
