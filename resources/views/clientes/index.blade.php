<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif
                <div>
                    @if(auth()->user()->role === 'admin')
                    <a href="{{ route('clientes.create') }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        + Nuevo Cliente
                    </a>
                    @endif
                </div>
                <table class="min-w-full border-collapse block md:table">
                    <thead class="block md:table-header-group">
                    <tr class="border border-grey-500 md:border-none block md:table-row">
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Nombre</th>
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Email</th>
                        <th class="bg-gray-600 p-2 text-white font-bold md:border md:border-grey-500 text-left block md:table-cell">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="block md:table-row-group">
                    @foreach ($clientes as $cliente)
                        <tr class="bg-gray-100 border border-grey-500 md:border-none block md:table-row">
                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">{{ $cliente->nombre }}</td>
                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">{{ $cliente->email }}</td>
                            <td class="p-2 md:border md:border-grey-500 text-left block md:table-cell">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Editar</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
