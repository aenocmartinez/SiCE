@extends("plantillas.principal")

@section("title", "Módulo de Cambios de Cursos")
@section("description", "")

@php
    $criterio = isset($criterio) ? $criterio : '';
@endphp

@section("content")

@php
    $criterio = isset($criterio) ? $criterio : '';
    $route = "cambios-traslados.index";
    $page = 1;
    if (strlen($criterio)>0) {
        $route = "cambios-traslados.buscador-paginador";
    }
@endphp

<div class="row mb-3">

    <div class="row">
        
        <div class="col-lg-8 col-sm-12">
            <form method="post" action="{{ route('cambios-traslados.buscador') }}">
                @csrf
                <div class="pt-0">
                    <div class="input-group">                
                        <button class="btn btn-alt-primary" type="submit">
                            <i class="fa fa-search me-1 opacity-50"></i> 
                        </button>
                        <input type="text" class="form-control" 
                        id="criterio" 
                        name="criterio" 
                        value="{{ $criterio }}"
                        placeholder="Buscar en el tablero">  
                    </div>
                </div>
            </form>
        </div>  

        <div class="col-lg-4 col-sm-12 col-xs-12 text-end">
            <a href="{{ route('cambios-traslados.create') }}" class="btn btn-lg btn-info">
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Iniciar un nuevo trámite
            </a>
        </div>        

    </div>
</div>    

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                <thead>
                    <tr class="text-center">
                        <th>Participante</th>
                        <th class="text-center">Formulario</th>
                        <th>Periodo</th>
                        <th>Curso Inicial</th>
                        <th>Nuevo Curso</th>
                        <th class="text-center">Trámite</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paginate->Records() as $item)
                    <tr class="fs-xs">
                        <td>
                            {{ $item->getParticipanteInicial()->getNombreCompleto() }}<br>
                            <small>{{ $item->getParticipanteInicial()->getDocumentoCompleto() }}</small>
                        </td>
                        <td class="text-center">
                            {{ $item->getFormulario()->getNumero() }}<br>
                            {{ $item->getFormulario()->getEstado() }}
                        </td>
                        <td class="texte-center">{{ $item->getPeriodo() }}</td>
                        <td class="text-center">{{ $item->getNombreCursoInicial() }} 
                            <br> {{ $item->getGrupoInicial()->getNombre() }}
                            <br> {{ $item->getGrupoInicial()->getDia() . " / " . $item->getGrupoInicial()->getJornada() }}
                        </td>
                        <td class="text-center">{{ $item->getNombreNuevoCurso() }} 
                            <br> {{ $item->getNuevoGrupo()->getNombre() }}
                            <br> {{ $item->getNuevoGrupo()->getDia() . " / " . $item->getNuevoGrupo()->getJornada() }}
                        </td>
                        <td class="text-center">{{ Src\infraestructure\util\ListaDeValor::tagMotivoCambioYTraslado($item->getAccion()) }}</td>
                        <td class="text-center">

                            @if ($item->getFormulario()->PendienteDePago() || $item->getFormulario()->RevisarComprobanteDePago())
                                <a href="{{ route('formularios.edit-legalizar-inscripcion', [$item->getFormulario()->getNumero()]) }}" 
                                        class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning"
                                        data-bs-toggle="tooltip" 
                                        title="Legalizar inscripción">
                                        Legalizar
                                </a>             
                            @endif
                                                    
                            <a href="{{ route('formulario-inscripcion.descargar-recibo-matricula', $item->getParticipanteInicial()->getId()) }}" 
                                    class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                    data-bs-toggle="tooltip" 
                                    title="Descargar recibo matrícula">
                                    Recibo
                            </a>
                            
                            <a href="{{ route('formularios.ver-detalle-inscripcion', $item->getFormulario()->getNumero()) }}" 
                                    class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                                    data-bs-toggle="tooltip" 
                                    title="Detalle de la inscripción">
                                    Ver
                            </a>                                                        
                        </td>
                    </tr>                    
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            No se encontraron registros
                        </td>
                    </tr>
                    @endforelse 
                </tbody>
            </table>    
            @if ($paginate->Records()) 
                @include('paginator', ['route' => 'cambios-traslados.index'])
            @endif
        </div>
    </div>
</div>


@endsection