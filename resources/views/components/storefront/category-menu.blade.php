@props([
    'types',
    'tipoAtual' => null,
])

@php
    $qPreserve = request()->filled('q') ? ['q' => request('q')] : [];
@endphp

<div {{ $attributes->merge(['class' => 'mt-8']) }}>
    <div class="-mx-1 flex gap-2 overflow-x-auto pb-2 snap-x snap-mandatory lg:flex-wrap lg:overflow-visible lg:snap-none">
        @php
            $allActive = $tipoAtual === null || $tipoAtual === '';
        @endphp
        <a
            href="{{ route('home', $qPreserve) }}"
            class="snap-start shrink-0 rounded-full px-5 py-2.5 text-sm font-medium shadow-sm ring-1 transition {{ $allActive ? 'bg-gray-900 text-white ring-gray-900 dark:bg-gray-100 dark:text-gray-900 dark:ring-gray-100' : 'bg-white text-gray-700 ring-gray-200/80 hover:shadow-md hover:ring-gray-300 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700' }}"
        >
            {{ __('Todos') }}
        </a>
        @foreach ($types as $type)
            @php
                $active = (string) $tipoAtual === (string) $type->id;
            @endphp
            <a
                href="{{ route('home', array_merge($qPreserve, ['tipo' => $type->id])) }}"
                class="snap-start shrink-0 whitespace-nowrap rounded-full px-5 py-2.5 text-sm font-medium shadow-sm ring-1 transition {{ $active ? 'bg-gray-900 text-white ring-gray-900 dark:bg-gray-100 dark:text-gray-900 dark:ring-gray-100' : 'bg-white text-gray-700 ring-gray-200/80 hover:shadow-md hover:ring-gray-300 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700' }}"
            >
                {{ $type->name }}
            </a>
        @endforeach
    </div>
</div>
