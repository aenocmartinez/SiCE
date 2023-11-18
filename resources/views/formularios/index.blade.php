@extends("plantillas.principal")

@section("title", "Listado de inscripciones")
@section("description", "Listado de participantes inscritos según periodo.")

@section("content")
 

<div class="row">

    <div class="block block-rounded block-content">

            <form class="row row-cols-lg-auto align-items-center pb-3" action="{{ route('formularios.buscar-inscritos')}}" method="POST">
            @csrf
                <div class="col-xl-4">     
                    <select class="form-select @error('periodo') is-invalid @enderror" id="periodo" name="periodo">
                        <option value="">Selecciona periodo</option>
                        @foreach ($periodos as $p)
                            @php
                                $selected = '';
                                if (!isset($periodo)) {
                                    $selected = ($loop->index == 0 ? 'selected' : '');
                                } else { 
                                    $selected = ($p->getId() == $periodo ? 'selected' : '');
                                }
                            @endphp             
                            <option value="{{ $p->getId() }}" {{ $selected }}>{{ $p->getNombre() }}</option>
                        @endforeach
                    </select>
                    @error('periodo')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror                                            
                </div>
    
                <div class="col-xl-4">
                    <select class="form-select" id="estado" name="estado">
                        <option value="">[Mostrar todo]</option>
                        @foreach ($estadoFormulario as $e)   
                            @php
                                $selected = (isset($estado) && $e['nombre'] == $estado ? 'selected' : '');
                            @endphp                                           
                            <option value="{{ $e['value'] }}" {{ $selected }}>{{ $e['nombre'] }}</option>
                        @endforeach
                    </select>                           
                </div>  
                
                <div class="col-xl-4">                
                    <button class="btn btn-info">
                    <i class="fa fa-search me-1 opacity-50"></i>
                        Buscar inscripciones
                    </button>
                </div>

            </form>
        

            <!-- Tabla -->
            <table class="table table-vcenter mt-4">
                <tr>
                    <thead>
                        <th>Formulario</th>
                        <th>Participante</th>
                        <th>Documento</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Fecha inscripción</th>
                        <th></th>
                    </thead>
                </tr>
                @forelse ($formularios as $f)
                <tr class="fs-xs">
                    <td>{{ $f->getNumero() }}</td>
                    <td>{{ $f->getParticipanteNombreCompleto() }}</td>
                    <td>{{ $f->getParticipanteTipoYDocumento() }}</td>
                    <td>
                        {{ $f->getGrupoNombreCurso() }}. <br>Grupo: {{ $f->getGrupoDia() . " / ".$f->getGrupoJornada() }}
                    </td>
                    <td>{{ $f->getEstado() }}</td>
                    <td>{{ $f->getFechaCreacion() }}</td>
                    <td class="text-center">
                    @if ($f->getEstado() == 'Pendiente de pago')                        
                        <a href="{{ route('formularios.edit-legalizar-inscripcion', [$f->getNumero()]) }}" 
                                class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                data-bs-toggle="tooltip" 
                                title="Legalizar inscripción">
                                Legalizar
                        </a>                        
                    @endif
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="10">No hay formularios para mostrar</td>
                </tr>
                @endforelse 
            </table>
            <!-- Fin tabla -->

    </div>

</div>


@endsection