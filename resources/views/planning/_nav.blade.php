<nav class="mb-6 border-b border-slate-800/60 pb-3 text-xs sm:text-sm text-slate-400 flex flex-wrap items-center gap-2">
    <a href="{{ route('dashboard') }}" class="hover:text-slate-100 transition">Inicio</a>
    <span class="text-slate-600">/</span>
    <a href="{{ route('planning.index') }}" class="hover:text-slate-100 transition">Planificación</a>
    <span class="text-slate-600">—</span>

    <a href="{{ route('planning.plans.index') }}" class="hover:text-slate-100 transition">Planes</a>
    <span class="text-slate-600">·</span>
    <a href="{{ route('planning.dashboards.index') }}" class="hover:text-slate-100 transition">Dashboards</a>
</nav>
