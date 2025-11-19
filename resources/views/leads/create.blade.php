@extends('layouts.crm')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Novo Lead</h1>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="{{ route('leads.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome:</label>
            <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nome') border-red-500 @enderror">
            @error('nome')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="telefone" class="block text-gray-700 text-sm font-bold mb-2">Telefone:</label>
            <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}" required
                   placeholder="Ex: 5511999999999"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('telefone') border-red-500 @enderror">
            @error('telefone')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
            <select name="status" id="status" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror">
                <option value="novo" {{ old('status') === 'novo' ? 'selected' : '' }}>Novo</option>
                <option value="em_contato" {{ old('status') === 'em_contato' ? 'selected' : '' }}>Em Contato</option>
                <option value="convertido" {{ old('status') === 'convertido' ? 'selected' : '' }}>Convertido</option>
                <option value="perdido" {{ old('status') === 'perdido' ? 'selected' : '' }}>Perdido</option>
            </select>
            @error('status')
                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Salvar
            </button>
            <a href="{{ route('leads.index') }}" class="text-gray-600 hover:text-gray-800">Cancelar</a>
        </div>
    </form>
</div>
@endsection
