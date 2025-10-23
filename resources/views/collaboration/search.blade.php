{{-- resources/views/collab/search-results.blade.php --}}
@extends('layouts.app')
@section('title', 'Resultados de búsqueda')
@section('content')
<div class="space-y-6 text-slate-100">
  <h1 class="text-xl font-semibold">Resultados para “{{ $q }}”</h1>

  <a class="text-sm underline" href="{{ route('collab.index', ['channel'=>$channelId]) }}">← Volver al canal</a>

  <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-4">
    <h2 class="font-semibold mb-3">Mensajes</h2>
    @forelse($messages as $m)
      <div class="text-sm border-b border-slate-800/50 py-2">
        <div class="text-slate-300">{{ $m->content }}</div>
        <div class="text-xs text-slate-500">#{{ $m->id }}</div>
      </div>
    @empty <p class="text-sm text-slate-400">Sin coincidencias.</p>
    @endforelse
  </section>

  <section class="bg-slate-950/60 border border-slate-800/70 rounded-2xl p-4">
    <h2 class="font-semibold mb-3">Archivos</h2>
    @forelse($files as $f)
      <div class="text-sm border-b border-slate-800/50 py-2">
        <div class="text-slate-300">{{ $f->file_name }}</div>
        <div class="text-xs text-slate-500">{{ \Illuminate\Support\Str::upper($f->mime_type ?? '') }}</div>
      </div>
    @empty <p class="text-sm text-slate-400">Sin coincidencias.</p>
    @endforelse
  </section>
</div>
@endsection
