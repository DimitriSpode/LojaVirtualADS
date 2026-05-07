<x-storefront-layout :title="config('app.name')">
    <x-storefront.browser-bar class="mb-2" />

    <x-storefront.brand-row />

    <x-storefront.category-menu :types="$types" :tipo-atual="$tipoAtual" />

    <x-storefront.section-title />

    @if ($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-gray-300 bg-white/80 px-8 py-16 text-center dark:border-gray-700 dark:bg-gray-900/80">
            <p class="text-gray-600 dark:text-gray-400">{{ __('Nenhum produto disponível no momento.') }}</p>
            @if ($tipoAtual)
                <a href="{{ route('home') }}" class="mt-4 inline-block text-sm font-medium text-gray-900 underline dark:text-gray-100">
                    {{ __('Ver todos os produtos') }}
                </a>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($products as $product)
                <x-storefront.product-card :product="$product" />
            @endforeach
        </div>
    @endif

    <x-storefront.product-modal />
</x-storefront-layout>
