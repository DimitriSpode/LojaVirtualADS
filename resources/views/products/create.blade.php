<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cadastro de produto') }}
        </h2>
    </x-slot>

    <div class="w-full min-w-0 max-w-6xl bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow mx-auto overflow-hidden">
        @if (session('status'))
            <p class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-4 py-3 text-green-800 dark:text-green-200 text-sm">
                {{ session('status') }}
            </p>
        @endif

        @if ($errors->any())
            <ul class="mb-4 rounded-md bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 px-4 py-3 text-red-800 dark:text-red-200 text-sm list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif


        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center mb-6 min-w-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo produto</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/types') }}" class="shrink-0 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-center">Gerenciar tipos</a>
                <a href="{{ url('/products') }}" class="shrink-0 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-center">Voltar à lista</a>
            </div>
        </div>

        <form action="{{ url('products/new') }}" method="POST"
              enctype="multipart/form-data" class="w-full bg-white
        dark:bg-gray-800 p-6 rounded-lg shadow">
            @csrf

            <x-meu-input name="name" label="Nome:" type="text" value="{{ old('name') }}" required />
            <x-meu-input name="description" label="Descrição:" as="textarea" rows="3" cols="40">{{ old('description') }}</x-meu-input>
            <x-meu-input name="quantity" label="Quantidade:" type="number" value="{{ old('quantity') }}" min="0" required />
            <x-meu-input name="price" label="Preço:" type="number" value="{{ old('price') }}" step="0.01" min="0" required />

            <div
                class="mb-4"
                x-data="{
                    imagePreview: null,
                    pickPreview(event) {
                        if (this.imagePreview) {
                            URL.revokeObjectURL(this.imagePreview);
                            this.imagePreview = null;
                        }
                        const file = event.target.files?.[0];
                        if (file && file.type.startsWith('image/')) {
                            this.imagePreview = URL.createObjectURL(file);
                        }
                    },
                }"
            >
                <label class="block mb-1 text-gray-700 dark:text-gray-300" for="product-image">Imagem:</label>
                <input
                    id="product-image"
                    name="image"
                    type="file"
                    accept="image/*"
                    class="w-full p-2 rounded border dark:bg-gray-700 dark:text-white"
                    x-on:change="pickPreview($event)"
                />
                <div class="mt-3" x-show="imagePreview" x-cloak>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ __('Pré-visualização') }}</p>
                    <img
                        :src="imagePreview"
                        alt="{{ __('Pré-visualização da imagem do produto') }}"
                        class="max-h-48 w-auto rounded-lg border border-gray-300 dark:border-gray-600 object-contain bg-gray-50 dark:bg-gray-900"
                    />
                </div>
            </div>

            <x-meu-input name="type_id" label="Tipo:" as="select" required>
                <option value="">Selecione…</option>
                @foreach ($types as $type)
                    <option value="{{ $type->id }}" @selected(old('type_id') == $type->id)>{{ $type->name }}</option>
                @endforeach
            </x-meu-input>
            <button type="submit" class="mt-2 w-full sm:w-auto bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Salvar
            </button>
        </form>
    </div>
</x-app-layout>
