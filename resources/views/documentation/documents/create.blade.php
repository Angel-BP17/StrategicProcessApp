@extends('layouts.app')

@section('title', 'Nuevo documento')

@section('content')
    <div class="container mx-auto px-0 sm:px-4">
        @include('documentation._nav')

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">Gestión documental</p>
                <h1 class="text-3xl font-semibold text-slate-100">Registrar documento</h1>
                <p class="text-sm text-slate-400 mt-1">Captura la metadata inicial del documento para iniciar su
                    trazabilidad.</p>
            </div>
            <a href="{{ route('documents.index') }}"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-700/60 px-4 py-2 text-xs sm:text-sm font-semibold text-slate-300 hover:text-slate-100 hover:border-slate-500 transition">
                ← Volver
            </a>
        </div>

        @if ($errors->any())
            <div
                class="mb-6 rounded-2xl border border-rose-500/40 bg-rose-500/10 px-5 py-4 text-sm text-rose-200 shadow-lg shadow-rose-950/30">
                <p class="font-semibold">Por favor corrige los siguientes campos:</p>
                <ul class="mt-2 space-y-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('documentation.documents._form', [
            'action' => route('documents.store'),
            'method' => 'POST',
            'document' => $document,
            'categories' => $categories,
            'statuses' => $statuses,
            'submitLabel' => 'Guardar documento',
            'withFileUpload' => true,
        ])
    </div>
@endsection
