@props([
    /**
     * Prioridade: public/images/store-logo.png|.jpg|.webp|.svg (customização sua).
     * Se nenhum existir, usa o SVG padrão versionado em public/images/store-logo-default.svg (sem URL externa).
     */
])

@php
    $logoSrc = asset('images/store-logo-default.svg');
    foreach (['images/store-logo.png', 'images/store-logo.jpg', 'images/store-logo.webp', 'images/store-logo.svg'] as $rel) {
        if (file_exists(public_path($rel))) {
            $logoSrc = asset($rel);
            break;
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-white shadow-md ring-1 ring-gray-200/70 px-3 py-3 sm:px-4 dark:bg-gray-900 dark:ring-gray-800']) }}>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">
        {{-- Logo (home) — arquivo local; evita hotlink bloqueado (ex.: iStock) --}}
        <a
            href="{{ route('home') }}"
            class="flex shrink-0 items-center justify-center self-start rounded-xl bg-gray-50 p-1.5 ring-1 ring-gray-200/80 transition hover:bg-white hover:ring-gray-300 dark:bg-gray-800 dark:ring-gray-700 dark:hover:bg-gray-800"
            title="{{ __('Início') }}"
        >
            <img
                src="{{ $logoSrc }}"
                alt="{{ config('app.name') }}"
                class="h-9 w-auto max-w-[160px] object-contain sm:h-10"
                width="200"
                height="44"
                loading="eager"
                decoding="async"
            />
        </a>

        {{-- Pesquisa de produtos --}}
        <form method="GET" action="{{ route('home') }}" class="min-w-0 flex-1 flex items-center gap-2">
            @if (request()->filled('tipo'))
                <input type="hidden" name="tipo" value="{{ request('tipo') }}">
            @endif
            <div class="w-full rounded-full bg-gray-100 shadow-inner ring-1 ring-gray-200/80 px-1 py-1 focus-within:ring-2 focus-within:ring-gray-900/15 dark:bg-gray-800 dark:ring-gray-700 dark:focus-within:ring-gray-300/20 transition">
                <div class="flex items-center gap-2 px-3">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="{{ __('Pesquisar produtos…') }}"
                        autocomplete="off"
                        class="min-w-0 flex-1 bg-transparent py-2 text-sm text-gray-900 outline-none placeholder:text-gray-500 dark:text-gray-100 dark:placeholder:text-gray-400"
                    />
                </div>
            </div>
            <button
                type="submit"
                class="shrink-0 rounded-full bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
            >
                {{ __('Buscar') }}
            </button>
        </form>

        <x-storefront.header-auth />
    </div>
</div>
