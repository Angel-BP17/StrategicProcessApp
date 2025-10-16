@extends('layouts.app')
@section('content')
    <div class="container mx-auto p-4">
        @include('planning._nav')
        <h1 class="text-xl font-bold mb-4">Nuevo plan</h1>

        <form method="POST" action="{{ route('planning.plans.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm">Título</label>
                <input name="title" class="w-full border rounded p-2" required />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm">Inicio</label>
                    <input type="date" name="start_date" class="w-full border rounded p-2" required />
                </div>
                <div>
                    <label class="block text-sm">Fin</label>
                    <input type="date" name="end_date" class="w-full border rounded p-2" required />
                </div>
            </div>
            <div>
                <label class="block text-sm">Descripción</label>
                <textarea name="description" class="w-full border rounded p-2" rows="4"></textarea>
            </div>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Guardar</button>
        </form>
    </div>
@endsection
