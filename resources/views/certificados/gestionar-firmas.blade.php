@extends("plantillas.principal")

@php
    $titulo = "Gestión de Firmas para Certificados";
@endphp

@section("title", $titulo)

@section("description", "Cargue o actualice las firmas que aparecerán en los certificados")

@section("seccion")
    <a class="link-fx" href="{{ route('dashboard') }}">
        Dashboard
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

<div class="block block-rounded">
    <div class="block-content">
        <form action="{{ route('firmas.guardar') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{-- Firmante 1 --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Firma No. 1</h5>

                    <div class="mb-3">
                        <label for="nombre_firmante1" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_firmante1" name="nombre_firmante1"
                            value="{{ old('nombre_firmante1', $firma->getNombreFirmante1()) }}">
                    </div>

                    <div class="mb-3">
                        <label for="cargo_firmante1" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo_firmante1" name="cargo_firmante1"
                            value="{{ old('cargo_firmante1', $firma->getCargoFirmante1()) }}">
                    </div>

                    <div class="mb-3">
                        <label for="ruta_firma1" class="form-label">Firma digitalizada</label>
                        <input type="file" class="form-control" id="ruta_firma1" name="ruta_firma1">
                    </div>

                    @if ($firma->existe())
                        <div class="mt-3">
                            <p class="mb-1">Firma actual:</p>
                            <img src="{{ asset('storage/' . $firma->getRutaFirma1()) }}" alt="Firma 1" class="img-fluid" style="max-height: 120px;">
                        </div>
                    @endif
                </div>

                {{-- Firmante 2 --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Firma No. 2</h5>

                    <div class="mb-3">
                        <label for="nombre_firmante2" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_firmante2" name="nombre_firmante2"
                            value="{{ old('nombre_firmante2', $firma->getNombreFirmante2()) }}">
                    </div>

                    <div class="mb-3">
                        <label for="cargo_firmante2" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo_firmante2" name="cargo_firmante2"
                            value="{{ old('cargo_firmante2', $firma->getCargoFirmante2()) }}">
                    </div>

                    <div class="mb-3">
                        <label for="ruta_firma2" class="form-label">Firma digitalizada</label>
                        <input type="file" class="form-control" id="ruta_firma2" name="ruta_firma2">
                    </div>

                    @if ($firma->existe())
                        <div class="mt-3">
                            <p class="mb-1">Firma actual:</p>
                            <img src="{{ asset('storage/' . $firma->getRutaFirma2()) }}" alt="Firma 2" class="img-fluid" style="max-height: 120px;">
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center mt-4 mb-2">
                <button type="submit" class="btn btn-primary px-4 py-2">Guardar Firmas</button>
            </div>
        </form>
    </div>
</div>

@endsection
