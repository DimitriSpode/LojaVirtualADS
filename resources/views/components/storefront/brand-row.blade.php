<div {{ $attributes->merge(['class' => 'mt-8 flex items-center gap-4 px-1']) }}>
    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-gray-200/80 dark:bg-gray-900 dark:ring-gray-700">
        <x-application-logo class="h-9 w-9 fill-current text-gray-700 dark:text-gray-300" />
    </div>
    <div>
        <p class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white sm:text-xl">
            {{ config('app.name') }}
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Sua loja virtual') }}</p>
    </div>
</div>
