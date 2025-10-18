{{-- resources/views/collaboration.blade.php --}}
@extends('layouts.app')

@section('title', 'Colaboración y Comunicación Digital')

@section('content')
    <div class="space-y-8">
        {{-- Encabezado --}}
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-800">Colaboración y Comunicación Digital</h1>
                <p class="text-zinc-500 mt-1 text-sm">
                    Espacio para fortalecer el trabajo en equipo, compartir información y coordinar tareas de manera digital.
                </p>
            </div>
            <a href="#nuevo-mensaje"
                class="inline-flex items-center gap-2 bg-[#26BBFF] text-white px-4 py-2 rounded-lg hover:bg-[#26BBFF]/90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Mensaje
            </a>
        </header>

        {{-- Sección: Mensajería / Anuncios --}}
        <section class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-zinc-800">Mensajería y anuncios</h2>
                <button
                    class="text-sm text-[#26BBFF] hover:text-[#201A2F] transition font-medium">Ver todos</button>
            </div>

            {{-- Ejemplo de mensajes --}}
            <div class="space-y-4">
                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800">Actualización del plan estratégico</h3>
                    <p class="text-sm text-zinc-600 mt-1">
                        Se agregó una nueva meta en el plan institucional 2025. Revisa los objetivos actualizados.
                    </p>
                    <div class="mt-2 text-xs text-zinc-500">Publicado por <span class="font-medium">Dirección General</span> —
                        hace 3 horas</div>
                </div>

                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800">Invitación al taller de innovación</h3>
                    <p class="text-sm text-zinc-600 mt-1">
                        El área de Innovación te invita a participar en el taller sobre mejora continua digital.
                    </p>
                    <div class="mt-2 text-xs text-zinc-500">Publicado por <span class="font-medium">Comité Académico</span> —
                        hace 1 día</div>
                </div>
            </div>
        </section>

        {{-- Sección: Documentos compartidos --}}
        <section class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-zinc-800">Documentos compartidos</h2>
                <button
                    class="text-sm text-[#26BBFF] hover:text-[#201A2F] transition font-medium">Subir documento</button>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#26BBFF]" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                        Plan Estratégico 2025.pdf
                    </h3>
                    <p class="text-xs text-zinc-500 mt-1">Subido por: <span class="font-medium">Admin</span></p>
                    <div class="mt-2">
                        <a href="#" class="text-sm text-[#26BBFF] hover:underline">Ver / Descargar</a>
                    </div>
                </div>

                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#26BBFF]" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                        Agenda Taller Innovación.docx
                    </h3>
                    <p class="text-xs text-zinc-500 mt-1">Subido por: <span class="font-medium">Usuario1</span></p>
                    <div class="mt-2">
                        <a href="#" class="text-sm text-[#26BBFF] hover:underline">Ver / Descargar</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Sección: Calendario / eventos colaborativos --}}
        <section class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-zinc-800">Calendario colaborativo</h2>
                <button
                    class="text-sm text-[#26BBFF] hover:text-[#201A2F] transition font-medium">Agregar evento</button>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800">Reunión de seguimiento</h3>
                    <p class="text-sm text-zinc-600 mt-1">20 de octubre, 10:00 a.m.</p>
                    <p class="text-xs text-zinc-500 mt-2">Área: Dirección Estratégica</p>
                </div>

                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800">Sesión de revisión de KPIs</h3>
                    <p class="text-sm text-zinc-600 mt-1">25 de octubre, 12:00 p.m.</p>
                    <p class="text-xs text-zinc-500 mt-2">Área: Planeación Institucional</p>
                </div>

                <div class="border rounded-xl p-4 hover:shadow transition">
                    <h3 class="font-medium text-zinc-800">Taller de innovación docente</h3>
                    <p class="text-sm text-zinc-600 mt-1">30 de octubre, 3:00 p.m.</p>
                    <p class="text-xs text-zinc-500 mt-2">Área: Calidad educativa</p>
                </div>
            </div>
        </section>
    </div>
@endsection
