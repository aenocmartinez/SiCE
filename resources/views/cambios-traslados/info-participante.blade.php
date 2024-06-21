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
                            G{{ $f->getGrupoId()  }}: 
                            {{ $f->getGrupoDia()  }} / {{ $f->getGrupoJornada() }}<br>{{ $f->getGrupoModalidad() }}
                        </small>
                    </td>
                    <td class="text-center">
                        {{ $f->getEstado() }}
                        @if ($f->tieneConvenio())
                            Convenio: {{ $f->getConvenioNombre() }}
                        @endif
                    </td>

                    <input type="hidden" name="numero_formulario" value="{{ $f->getNumero() }}">
                    <td class="text-center">
                        <select class="form-select fs-xs text-center" name="motivo" id="motivo" class="form">
                            @foreach ($motivos_de_cambios as $index => $motivo)
                                @if ($index == 0)                                
                                    <option value="{{ $motivo['value'] }}">{{ $motivo['nombre'] }}</option>
                                @endif
                            @endforeach
                        </select>                            
                    </td>  
                    <td>   
                        <a href="{{ route('cambios-traslados.form-tramite', [$f->getNumero()]) }}" class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-info">
                                Continuar
                        </a>
                        <!-- <button type="submit" class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-info">
                            Continuar
                        </button> -->
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


@endsection