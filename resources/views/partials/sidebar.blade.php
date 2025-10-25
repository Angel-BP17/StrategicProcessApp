@php
    // Íconos SVG “custom” (clases se ajustan según estado activo)
    $icons = [
        'planning' =>
            '<svg class="w-5 h-5 {CLR}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M4 11h16M4 19h16M5 7h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z"/></svg>',
        'alliances' =>
            '<svg class="w-5 h-5 {CLR}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11a4 4 0 118 0v2a4 4 0 11-8 0v-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l4 4m10-4l4 4"/></svg>',
        'documents' =>
            '<svg class="w-5 h-5 {CLR}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h7l3 3v7a2 2 0 01-2 2H7a2 2 0 01-2-2V9a2 2 0 012-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 7v3h3"/></svg>',
        'innovation' =>
            '<svg class="w-5 h-5 {CLR}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v4m0 10v4m7-7h-4M9 12H5m9.657-6.657l-2.829 2.829M8.172 15.828l-2.829 2.829M15.828 15.828l2.829 2.829M8.172 8.172L5.343 5.343"/></svg>',
        'quality' =>
            '<svg class="w-5 h-5 {CLR}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l7 4v4l-7 4-7-4v-4l7-4z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>',
        'collab' =>
            '<svg class="w-5 h-5 {CLR}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v7l-3-2-3 2v-7a2 2 0 012-2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h6a2 2 0 012 2v8l-4-2-4 2V8a2 2 0 012-2z"/></svg>',
    ];

    $items = [
        [
            'key' => 'planning',
            'label' => 'Planificación institucional',
            'href' => route('planning.plans.index'),
            'match' => 'planning*',
        ],
        [
            'key' => 'alliances',
            'label' => 'Alianzas y convenios',
            'href' => route('alliances.index'),
            'match' => 'alliances*',
        ],
        [
            'key' => 'documents',
            'label' => 'Documentación y evidencias',
            'href' => url('/documents'),
            'match' => 'documents*',
        ],
        [
            'key' => 'innovation',
            'label' => 'Innovación y mejora continua',
            'href' => url('/innovacion-mejora-continua'),
            // Ajuste del match para tu prefijo real
            'match' => 'innovacion-mejora-continua*',
        ],
        [
            'key' => 'quality',
            'label' => 'Calidad educativa',
            'href' => route('quality.index'),
            'match' => 'quality*',
        ],
        [
            'key' => 'collab',
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
            @php
                $active = request()->is($it['match']);
                $wrapper = $active
                    ? 'bg-sky-500/15 text-sky-200 border border-sky-400/30 shadow-lg shadow-sky-500/20'
                    : 'text-slate-300 hover:text-slate-100 hover:bg-slate-900/60 border border-transparent';
                $iconColor = $active ? 'text-sky-300' : 'text-slate-400 group-hover:text-slate-200';
                $iconSvg = isset($icons[$it['key']]) ? str_replace('{CLR}', $iconColor, $icons[$it['key']]) : ''; // fallback sin icono
            @endphp

            <a href="{{ $it['href'] }}"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ $wrapper }}">
                {!! $iconSvg !!}
                <span class="truncate">{{ $it['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>
