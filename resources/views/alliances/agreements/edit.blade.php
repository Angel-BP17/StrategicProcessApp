@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <h2 class="text-xl font-bold mb-4">Editar Convenio</h2>

    <form action="{{ route('agreements.update', $agreement->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Socio</label>
            <select name="partner_id" class="border rounded w-full p-2" required>
                @foreach($partners as $partner)
                    <option value="{{ $partner->id }}" {{ $agreement->partner_id == $partner->id ? 'selected' : '' }}>
                        {{ $partner->name }} ({{ $partner->type }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1">Título del convenio</label>
            <input type="text" name="title" value="{{ old('title', $agreement->title) }}" class="border rounded w-full p-2" required>
        </div>

        <div class="flex gap-4">
            <div>
                <label class="block mb-1">Fecha Inicio</label>
                <input type="date" name="start_date" value="{{ old('start_date', $agreement->start_date) }}" class="border rounded p-2" required>
            </div>
            <div>
                <label class="block mb-1">Fecha Fin</label>
                <input type="date" name="end_date" value="{{ old('end_date', $agreement->end_date) }}" class="border rounded p-2" required>
            </div>
        </div>

        <div>
            <label class="block mb-1">Estado</label>
            <input type="text" name="status" value="{{ old('status', $agreement->status) }}" class="border rounded w-full p-2" required>
        </div>

        <div>
            <label class="block mb-1">Fecha de renovación</label>
            <input type="date" name="renewal_date" value="{{ old('renewal_date', $agreement->renewal_date) }}" class="border rounded w-full p-2">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="electronic_signature" value="1" {{ $agreement->electronic_signature ? 'checked' : '' }}>
            <label>Firma Electrónica</label>
        </div>

        <!-- Botones Actualizar y Cancelar -->
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Actualizar
            </button>
            <a href="{{ route('alliances.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
