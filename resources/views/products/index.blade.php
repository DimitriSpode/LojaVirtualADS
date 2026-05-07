<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listar produtos') }}
        </h2>
    </x-slot>

    <div>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('status') }}</span>
        </div>
    </div>
    <div class="w-full min-w-0 max-w-6xl bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow mx-auto overflow-hidden">
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center mb-6 min-w-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Produtos</h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/types') }}" class="shrink-0 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-center">Tipos</a>
                <a href="{{ url('products/new') }}" class="shrink-0 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">Cadastrar</a>
            </div>
        </div>
        <div class="w-full min-w-0 overflow-x-auto">
            <table class="w-full min-w-[640px] table-auto border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Imagem</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Nome</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Preço</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Quantidade</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Tipo</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <td class="px-4 py-2 text-gray-900 dark:text-white">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}" alt="Imagem do produto" class="w-20 h-20 object-cover rounded">
                            </td>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $product->price }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $product->quantity }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $product->type->name }}</td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ url('/products/update', ['id' => $product->id]) }}" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">Editar</a>
                                    <a href="{{ url('/products/delete', ['id' => $product->id]) }}" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
