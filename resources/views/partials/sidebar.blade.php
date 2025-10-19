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

<aside class="w-64 shrink-0 border-r bg-white/90 backdrop-blur">
    <div class="px-4 py-4 border-b">
        <a href="{{ route('dashboard') }}" class="text-lg font-semibold">Instituto</a>
        <div class="text-xs text-zinc-500">Panel</div>
    </div>

    <nav class="p-2 space-y-1">
        @foreach ($items as $it)
            @php $active = request()->is($it['match']); @endphp
            <a href="{{ $it['href'] }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm
                {{ $active ? 'bg-zinc-900 text-white' : 'text-zinc-700 hover:bg-zinc-100' }}">
                <span>{{ $it['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>

