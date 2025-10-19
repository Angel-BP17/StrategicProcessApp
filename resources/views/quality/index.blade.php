{{-- 1. Heredamos la plantilla principal (esta es la sintaxis correcta) --}}
@extends('layouts.app') 

{{-- 2. Rellenamos la sección 'header' (el título gris) --}}
@section('header')
<h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
    {{ __('Gestión de Calidad Educativa') }}
</h2>
@endsection

{{-- 3. Rellenamos la sección 'content' (el contenido principal) --}}
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-dark-purple shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h1 class="text-2xl font-medium text-white mb-6">
                Panel Principal de Calidad
            </h1>

            {{-- Grid de 4 tarjetas para los sub-módulos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <a href="{{ route('quality.audits.index') }}" class="block p-6 bg-night rounded-lg shadow hover:bg-smoky-black transition ease-in-out duration-150">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-white">Gestión de Auditorías</h5>
                    <p class="font-normal text-gray-400">Planificar, ejecutar y dar seguimiento a las auditorías internas y externas.</p>
                </a>

                <a href="{{ route('quality.surveys.index') }}" class="block p-6 bg-night rounded-lg shadow hover:bg-smoky-black transition ease-in-out duration-150">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-white">Gestión de Encuestas</h5>
                    <p class="font-normal text-gray-400">Crear y analizar encuestas de satisfacción estudiantil.</p>
                </a>

                <a href="{{ route('quality.accreditations.index') }}" class="block p-6 bg-night rounded-lg shadow hover:bg-smoky-black transition ease-in-out duration-150">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-white">Procesos de Acreditación</h5>
                    <p class="font-normal text-gray-400">Registrar y gestionar las certificaciones del instituto.</p>
                </a>

                <a href="{{ route('quality.evaluation-criteria.index') }}" class="block p-6 bg-night rounded-lg shadow hover:bg-smoky-black transition ease-in-out duration-150">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-white">Evaluación de Estándares</h5>
                    <p class="font-normal text-gray-400">Administrar rúbricas y criterios para la evaluación docente.</p>
                    {{-- Cambiamos el texto para que coincida con lo que acabamos de construir --}}
                </a>

            </div>
        </div>
    </div>
</div>
@endsection