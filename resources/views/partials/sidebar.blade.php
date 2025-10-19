@php
    $items = [
        ['label' => 'Planificación institucional', 'href' => route('planning.plans.index'), 'match' => 'planning*'],
        ['label' => 'Alianzas y convenios', 'href' => route('alliances.index'), 'match' => 'alliances*'],
        ['label' => 'Documentación y evidencias', 'href' => url('/documents'), 'match' => 'documents*'],
        ['label' => 'Innovación y mejora continua', 'href' => url('/innovacion-mejora-continua'), 'match' => 'innovation*'],
        ['label' => 'Calidad educativa', 'href' => url('/quality'), 'match' => 'quality*'],
        [
            'label' => 'Colaboración y comunicación digital',
            'href' => url('/collaboration'),
            'match' => 'collaboration*',
        ],
    ];
@endphp

<aside class="w-64 shrink-0 border-r border-slate-800/60 bg-slate-950/70 backdrop-blur-xl">
    <div class="px-4 py-5 border-b border-slate-800/60 bg-slate-950/70">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-slate-100 tracking-tight">Instituto</a>
        <div class="text-xs text-slate-400">Panel</div>
    </div>

    <nav class="p-3 space-y-1">
        @foreach ($items as $it)
            @php $active = request()->is($it['match']); @endphp
            <a href="{{ $it['href'] }}"
                class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition-all
                {{ $active
                    ? 'bg-sky-500/15 text-sky-200 border border-sky-400/30 shadow-lg shadow-sky-500/20'
                    : 'text-slate-300 hover:text-slate-100 hover:bg-slate-900/60 border border-transparent' }}">
                <span class="truncate">{{ $it['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>

