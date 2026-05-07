@props([
    'product',
])

@php
    $imageSrc = $product->image
        ? asset('storage/' . $product->image)
        : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg';

    $detail = [
        'name' => $product->name,
        'description' => $product->description ?? '',
        'price' => (float) $product->price,
        'quantity' => (int) $product->quantity,
        'image' => $imageSrc,
        'type' => $product->type?->name,
    ];
@endphp

<div {{ $attributes->merge(['class' => 'group flex flex-col rounded-2xl bg-white p-4 shadow-md ring-1 ring-gray-200/60 transition duration-300 hover:-translate-y-1 hover:shadow-xl dark:bg-gray-900 dark:ring-gray-800']) }}>
    <div class="relative overflow-hidden rounded-xl bg-gray-100 aspect-square dark:bg-gray-800">
        <img
            src="{{ $imageSrc }}"
            alt="{{ $product->name }}"
            class="h-full w-full object-cover transition duration-500 ease-out group-hover:scale-105"
        />
    </div>

    <div class="mt-4 flex flex-1 flex-col gap-2">
        <h3 class="text-base font-semibold text-gray-900 line-clamp-2 dark:text-white">{{ $product->name }}</h3>
        @if ($product->type)
            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">{{ $product->type->name }}</p>
        @endif
        <p class="text-sm text-gray-600 line-clamp-2 dark:text-gray-400">{{ \Illuminate\Support\Str::limit($product->description ?? '', 120) }}</p>
        <p class="mt-auto pt-2 text-lg font-bold text-gray-900 dark:text-white">
            R$ {{ number_format((float) $product->price, 2, ',', '.') }}
        </p>
        <button
            type="button"
            class="mt-2 w-full rounded-xl bg-gray-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
            @click="$dispatch('storefront-product', @js($detail))"
        >
            {{ __('Ver produto') }}
        </button>
    </div>
</div>
