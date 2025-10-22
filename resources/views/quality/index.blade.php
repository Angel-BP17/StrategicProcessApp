@extends('layouts.app')

@section('title', 'Gestión de Calidad Educativa') {{-- Título de la página --}}

{{-- @section('header') ... @endsection --}} {{-- Quitamos si layout maneja --}}

@section('content')
    {{-- Contenedor principal --}}
    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 text-slate-100">

        {{-- Cabecera Principal --}}
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                {{-- Miga de pan / Subtítulo --}}
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Panel Principal</p>
                {{-- Título Grande --}}
                <h1 class="text-3xl font-semibold">Gestión de Calidad Educativa</h1>
                {{-- Descripción Breve --}}
                <p class="text-slate-400 mt-1">Supervisa y gestiona auditorías, encuestas, acreditaciones y criterios de evaluación.</p>
            </div>
            
        </div>

        

        {{-- "Caja" Principal para las Tarjetas de Módulos --}}
        <div class="bg-slate-950/60 border border-slate-800/70 shadow-xl shadow-slate-900/50 rounded-2xl mb-10 overflow-hidden">
            {{-- Cabecera de la Caja --}}
            <div class="p-6 font-semibold text-lg border-b border-slate-800/70 text-slate-200">Módulos Disponibles</div>

            {{-- Grid de 4 tarjetas (ahora dentro de la caja principal) --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Tarjeta 1: Auditorías (con icono) --}}
                <a href="{{ route('quality.audits.index') }}"
                   class="group block p-6 bg-slate-900/70 border border-slate-800/70 rounded-xl shadow-md hover:bg-slate-800/60 hover:border-sky-500/50 transition ease-in-out duration-150">
                   <div class="flex items-center gap-4">
                       {{-- Icono SVG (Ejemplo: Checklist) --}}
                       <div class="flex-shrink-0 bg-sky-500/10 text-sky-400 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                       </div>
                       <div>
                           <h5 class="mb-1 text-lg font-semibold tracking-tight text-slate-100 group-hover:text-sky-300">Gestión de Auditorías</h5>
                           <p class="font-normal text-sm text-slate-400">Planificar, ejecutar y seguir auditorías internas/externas.</p>
                       </div>
                   </div>
                </a>

                {{-- Tarjeta 2: Encuestas (con icono) --}}
                <a href="{{ route('quality.surveys.index') }}"
                   class="group block p-6 bg-slate-900/70 border border-slate-800/70 rounded-xl shadow-md hover:bg-slate-800/60 hover:border-emerald-500/50 transition ease-in-out duration-150">
                    <div class="flex items-center gap-4">
                       {{-- Icono SVG (Ejemplo: Gráfico) --}}
                       <div class="flex-shrink-0 bg-emerald-500/10 text-emerald-400 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                       </div>
                       <div>
                            <h5 class="mb-1 text-lg font-semibold tracking-tight text-slate-100 group-hover:text-emerald-300">Gestión de Encuestas</h5>
                            <p class="font-normal text-sm text-slate-400">Diseñar, asignar y analizar encuestas de satisfacción.</p>
                       </div>
                   </div>
                </a>

                {{-- Tarjeta 3: Acreditaciones (con icono) --}}
                <a href="{{ route('quality.accreditations.index') }}"
                   class="group block p-6 bg-slate-900/70 border border-slate-800/70 rounded-xl shadow-md hover:bg-slate-800/60 hover:border-amber-500/50 transition ease-in-out duration-150">
                   <div class="flex items-center gap-4">
                       {{-- Icono SVG (Ejemplo: Medalla) --}}
                       <div class="flex-shrink-0 bg-amber-500/10 text-amber-400 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                       </div>
                       <div>
                            <h5 class="mb-1 text-lg font-semibold tracking-tight text-slate-100 group-hover:text-amber-300">Procesos de Acreditación</h5>
                            <p class="font-normal text-sm text-slate-400">Registrar y seguir certificaciones y acreditaciones.</p>
                       </div>
                   </div>
                </a>

                {{-- Tarjeta 4: Criterios (con icono) --}}
                <a href="{{ route('quality.evaluation-criteria.index') }}"
                   class="group block p-6 bg-slate-900/70 border border-slate-800/70 rounded-xl shadow-md hover:bg-slate-800/60 hover:border-rose-500/50 transition ease-in-out duration-150">
                    <div class="flex items-center gap-4">
                       {{-- Icono SVG (Ejemplo: Lápiz/Regla) --}}
                       <div class="flex-shrink-0 bg-rose-500/10 text-rose-400 rounded-lg p-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                       </div>
                       <div>
                            <h5 class="mb-1 text-lg font-semibold tracking-tight text-slate-100 group-hover:text-rose-300">Evaluación de Estándares</h5>
                            <p class="font-normal text-sm text-slate-400">Administrar rúbricas y criterios de evaluación docente.</p>
                       </div>
                   </div>
                </a>

            </div>
        </div> 
        {{-- Sección de KPIs/Indicadores (Opcional, pero visualmente atractivo) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-slate-900/70 border border-slate-800/70 rounded-xl p-4 text-center">
                <div class="text-3xl font-semibold text-sky-400">3</div>
                <div class="text-sm text-slate-400 mt-1">Auditorías en Progreso</div>
            </div>
             <div class="bg-slate-900/70 border border-slate-800/70 rounded-xl p-4 text-center">
                <div class="text-3xl font-semibold text-emerald-400">85%</div>
                <div class="text-sm text-slate-400 mt-1">Satisfacción (Últ. Encuesta)</div>
            </div>
             <div class="bg-slate-900/70 border border-slate-800/70 rounded-xl p-4 text-center">
                <div class="text-3xl font-semibold text-amber-400">2</div>
                <div class="text-sm text-slate-400 mt-1">Acreditaciones Próximas</div>
            </div>
        </div> 

    </div> 
@endsection