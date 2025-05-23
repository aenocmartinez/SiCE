@extends('plantillas.publico')

@section('nameSection', 'Certificado verificado')

@section('description')
    Este certificado fue emitido oficialmente por la Universidad Mayor de Cundinamarca y su validez ha sido verificada exitosamente.
@endsection

@section('content')
<div class="d-flex justify-content-center align-items-center min-vh-70">
    <div class="col-md-8 col-lg-6">
        <div class="block block-rounded shadow-sm border bg-white px-4 py-5">

            <div class="text-center mb-5">
                <h2 class="fw-semibold text-dark mb-1">Certificado válido</h2>
                <p class="text-muted mb-0">Universidad Mayor de Cundinamarca</p>
            </div>

            <div class="mb-4">
                <div class="small text-muted">Código de verificación</div>
                <div class="fw-medium text-dark">{{ $registro['uuid'] }}</div>
            </div>

            <div class="mb-4">
                <div class="small text-muted">Participante</div>
                <div class="fw-medium text-dark">{{ $registro['nombre_participante'] }}</div>
            </div>

            <div class="mb-4">
                <div class="small text-muted">Curso</div>
                <div class="fw-medium text-dark">{{ $registro['nombre_curso'] }}</div>
            </div>

            <div class="mb-4">
                <div class="small text-muted">Fecha de emisión</div>
                <div class="fw-medium text-dark">
                    {{ \Carbon\Carbon::parse($registro['fecha_generado'])->format('d/m/Y H:i') }}
                </div>
            </div>

            <div class="mb-4">
                <div class="small text-muted">Total de validaciones</div>
                <div class="fw-medium text-dark">{{ $registro['validaciones'] }}</div>
            </div>

            <div class="mb-4">
                <div class="small text-muted">Última validación</div>
                <div class="fw-medium text-dark">
                    @if ($registro['ultima_validacion'])
                        {{ \Carbon\Carbon::parse($registro['ultima_validacion'])->format('d/m/Y H:i') }}
                    @else
                        Nunca
                    @endif
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('certificados.descargarDesdeQR', $registro['uuid']) }}"
                   class="btn btn-primary px-4">
                   Descargar certificado
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
