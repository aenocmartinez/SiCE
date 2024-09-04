@extends("plantillas.principal")

@php
    $titulo = "Datos del participante";
@endphp

@section("title", $titulo)
@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")    

<div class="row">
    <div class="block block-rounded">
        <div class="block-content p-2">
            {{ $participante->getNombreCompleto() }} <br>
            {{ $participante->getDocumentoCompleto() }}
        </div>
    </div>
</div>

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                <tr>
                    <thead class="text-center">
                        <th style="width: 10%;">Formulario</th>
                        <th style="width: 10%;">Periodo</th>
                        <th style="width: 16%;">Curso</th>
                        <th style="width: 16%;">Estado</th>
                        <th style="width: 28%;">Acción</th>
                        <th style="width: 16%;"></th>
                    </thead>
                </tr>
                @forelse ($participante->formulariosParaCambiosYTramites() as $f)
                <tr class="fs-xs text-center">
                    <td>{{ $f->getNumero() }}</td>
                    <td>{{ $f->getGrupoCalendarioNombre() }}</td>
                    <td>
                        <a href="#" class="fs-sm">{{ $f->getGrupoNombreCurso() }}</a>
                        <br>
                        <small>
                            G{{ $f->getGrupoId() }}: 
                            {{ $f->getGrupoDia() }} / {{ $f->getGrupoJornada() }}<br>{{ $f->getGrupoModalidad() }}
                        </small>
                    </td>
                    <td class="text-center">
                        {{ $f->getEstado() }}
                        @if ($f->tieneConvenio())
                            Convenio: {{ $f->getConvenioNombre() }}
                        @endif
                    </td>
                    
                    <td class="text-center">
                        <select class="form-select fs-xs text-center" name="motivo" id="motivo-{{ $f->getNumero() }}" onchange="updateLink('{{ $f->getNumero() }}')">
                            @foreach ($motivos_de_cambios as $motivo)
                                <option value="{{ $motivo['value'] }}">{{ $motivo['nombre'] }}</option>
                            @endforeach
                        </select>
                    </td>
                    
                    <td>
                        <a id="continuar-{{ $f->getNumero() }}" href="{{ route('cambios-traslados.form-tramite', ['formulario' => $f->getNumero(), 'motivo' => $motivos_de_cambios[0]['value']]) }}" class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-info">
                            Continuar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="10">No hay formularios para mostrar</td>
                </tr>
                @endforelse
            </table>
        </div>
    </div>
</div>

<script>
    function updateLink(numeroFormulario) {
        var select = document.getElementById('motivo-' + numeroFormulario);
        var selectedMotivo = select.value;
        var link = document.getElementById('continuar-' + numeroFormulario);
        var url = "{{ route('cambios-traslados.form-tramite', ['formulario' => ':formulario', 'motivo' => ':motivo']) }}";
        url = url.replace(':formulario', numeroFormulario);
        url = url.replace(':motivo', selectedMotivo);
        link.href = url;
    }
</script>

@endsection
