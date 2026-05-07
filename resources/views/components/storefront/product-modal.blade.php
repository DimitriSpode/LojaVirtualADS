<div
    x-data="{ open: false, product: null }"
    x-on:storefront-product.window="product = $event.detail; open = true"
    x-on:keydown.escape.window="open = false"
>
    <div
        x-show="open"
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-[100] overflow-y-auto py-10 px-4 sm:py-16"
        style="display: none;"
    >
        <div
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
            @click="open = false"
        ></div>

        <div class="relative mx-auto flex min-h-[calc(100vh-5rem)] items-center justify-center">
            <div
                class="relative w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-700"
                @click.stop
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 rounded-lg p-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800"
                    @click="open = false"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <template x-if="product">
                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-xl bg-gray-100 aspect-square dark:bg-gray-800">
                            <img :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400" x-text="product.type"></p>
                            <h2 class="mt-1 text-xl font-bold text-gray-900 dark:text-white" x-text="product.name"></h2>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-line" x-text="product.description || '{{ __('Sem descrição.') }}'"></p>
                        </div>
                        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                R$ <span x-text="Number(product.price).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ __('Em estoque') }}: <span class="font-semibold text-gray-800 dark:text-gray-200" x-text="product.quantity"></span>
                            </p>
                        </div>
                        <button
                            type="button"
                            class="w-full rounded-xl bg-gray-900 py-3 text-sm font-semibold text-white hover:bg-gray-800 dark:bg-gray-100 dark:text-gray-900 dark:hover:bg-white"
                            @click="open = false"
                        >
                            {{ __('Fechar') }}
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
