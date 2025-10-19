@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h2 class="text-xl font-bold mb-4">Nuevo Convenio</h2>

    <form action="{{ route('agreements.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Socio</label>
            <select name="partner_id" class="border rounded w-full p-2" required>
                <option value="" disabled selected>Selecciona un socio</option>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}">
                        {{ $partner->name }} ({{ $partner->type }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">Título del convenio</label>
            <input type="text" name="title" value="{{ old('title') }}" class="border rounded w-full p-2" required>
        </div>

        <div class="flex gap-4">
            <div>
                <label class="block mb-1">Fecha Inicio</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="border rounded p-2" required>
            </div>
            <div>
                <label class="block mb-1">Fecha Fin</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="border rounded p-2" required>
            </div>
        </div>

        <div>
            <label class="block mb-1">Estado</label>
            <input type="text" name="status" value="{{ old('status') }}" class="border rounded w-full p-2" required>
        </div>

        <div>
            <label class="block mb-1">Fecha de renovación</label>
            <input type="date" name="renewal_date" value="{{ old('renewal_date') }}" class="border rounded w-full p-2">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="electronic_signature" value="1" {{ old('electronic_signature') ? 'checked' : '' }}>
            <label>Firma Electrónica</label>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Crear
        </button>
        <a href="{{ route('alliances.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
            Cancelar
        </a>
    </form>
</div>
@endsection
