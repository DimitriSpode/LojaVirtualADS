<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar tipo') }}
        </h2>
    </x-slot>

    <div class="w-full min-w-0 max-w-6xl bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-lg shadow mx-auto overflow-hidden">
        @if (session('success'))
            <p class="mb-4 rounded-md bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 px-4 py-3 text-green-800 dark:text-green-200 text-sm">
                {{ session('success') }}
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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar tipo</h1>
            <a href="{{ url('/types') }}" class="shrink-0 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white px-4 py-2 rounded hover:bg-gray-300 dark:hover:bg-gray-500 text-center">Voltar à lista</a>
        </div>

        <form action="{{ route('types.update') }}" method="POST" class="max-w-xl">
            @csrf
            <input type="hidden" name="id" value="{{ $type->id }}">

            <x-meu-input name="name" label="Nome do tipo:" type="text" value="{{ old('name', $type->name) }}" required maxlength="255" />

            <button type="submit" class="mt-2 w-full sm:w-auto bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Salvar alterações
            </button>
        </form>
    </div>
</x-app-layout>
