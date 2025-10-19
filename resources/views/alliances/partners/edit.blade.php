@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Editar socio</h3>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('partners.update', $partner) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del socio</label>
                    <input type="text" name="name" id="name"
                        value="{{ old('name', $partner->name) }}"
                        class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Tipo</label>
                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                        <option value="">-- Seleccione --</option>
                        <option value="Universidad" {{ $partner->type == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                        <option value="Empresa" {{ $partner->type == 'Empresa' ? 'selected' : '' }}>Empresa</option>
                        <option value="Instituto" {{ $partner->type == 'Instituto' ? 'selected' : '' }}>Instituto</option>
                        <option value="Fundación" {{ $partner->type == 'Fundación' ? 'selected' : '' }}>Fundación</option>
                        <option value="Otro" {{ $partner->type == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                    @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Correo o contacto</label>
                    <input type="text" name="contact" id="contact"
                        value="{{ old('contact', $partner->contact['email'] ?? '') }}"
                        class="form-control @error('contact') is-invalid @enderror">
                    @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="legal_representative" class="form-label">Representante legal</label>
                    <input type="text" name="legal_representative" id="legal_representative"
                        value="{{ old('legal_representative', $partner->legal_representative) }}"
                        class="form-control @error('legal_representative') is-invalid @enderror">
                    @error('legal_representative') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('alliances.index') }}" class="btn btn-secondary">Volver</a>
                    <button type="submit" class="btn btn-primary">Actualizar socio</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
