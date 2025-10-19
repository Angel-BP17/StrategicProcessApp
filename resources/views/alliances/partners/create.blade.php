@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Registrar nuevo socio</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('partners.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del socio</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tipo</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Seleccione --</option>
                        <option value="Universidad">Universidad</option>
                        <option value="Empresa">Empresa</option>
                        <option value="Instituto">Instituto</option>
                        <option value="Fundación">Fundación</option>
                        <option value="Otro">Otro</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Correo o contacto</label>
                    <input type="text" name="contact" id="contact"
                        value="{{ old('contact') }}"
                        class="form-control @error('contact') is-invalid @enderror">
                    @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="legal_representative" class="form-label">Representante legal</label>
                    <input type="text" name="legal_representative" id="legal_representative"
                        value="{{ old('legal_representative') }}"
                        class="form-control @error('legal_representative') is-invalid @enderror">
                    @error('legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('alliances.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">Guardar socio</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
