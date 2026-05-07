<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Fornecedores') }}
        </h2>
    </x-slot>

    <div class="w-full min-w-0 max-w-6xl bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow mx-auto overflow-hidden">
        @if (session('status'))
            <p class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-4 py-3 text-green-800 dark:text-green-200 text-sm">
                {{ session('status') }}
            </p>
        @endif
        @if (session('success'))
            <p class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-4 py-3 text-green-800 dark:text-green-200 text-sm">
                {{ session('success') }}
            </p>
        @endif

        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center mb-6 min-w-0">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fornecedores</h1>
            <a href="{{ url('/fornecedores/new') }}" class="shrink-0 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center">Cadastrar</a>
        </div>

        <div class="w-full min-w-0 overflow-x-auto">
            <table class="w-full min-w-[640px] table-auto border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Nome</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">E-mail</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Telefone</th>
                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr class="border-b border-gray-300 dark:border-gray-600">
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $supplier->name }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $supplier->email ?? '—' }}</td>
                            <td class="px-4 py-2 text-gray-900 dark:text-white">{{ $supplier->phone ?? '—' }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap justify-center gap-2">
                                    <a href="{{ url('/fornecedores/update', ['id' => $supplier->id]) }}" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700 text-sm">Editar</a>
                                    <a href="{{ url('/fornecedores/delete', ['id' => $supplier->id]) }}" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-sm" onclick="return confirm('Excluir este fornecedor?');">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">Nenhum fornecedor cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
