@extends("plantillas.principal")

@section("title", "Listado de inscripciones")
@section("description", "Listado de participantes inscritos según periodo.")

@section("content")

@php
    $periodo = isset($periodo) ? $periodo : '';
    $estado = isset($estado) ? $estado : '';
    $documento = isset($documento) ? $documento : '';
    $criterio = isset($criterio) ? $criterio : '';

    $route = "formularios.index";
    $page = 1;

    $criterio = ["periodo" => $periodo, "estado" => $estado, "documento" => $documento];   
    if (strlen($estado)>0 || strlen($periodo)>0 || strlen($documento)>0) {
        $route = "formularios.buscador-paginador";
    }

@endphp
 

<div class="row">

    <div class="block block-rounded block-content">

            <form class="row row-cols-lg-auto align-items-center pb-3" action="{{ route('formularios.buscar-inscritos')}}" method="POST">
            @csrf
                <div class="col-xl-3">     
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
    
                <div class="col-xl-3">
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

                <div class="col-xl-3">
                    <input type="text" name="documento" id="documento" value="{{ $documento }}" class="form-control" placeholder="Buscador">
                </div>
                
                <div class="col-xl-3">                
                    <button class="btn btn-info">
                    <i class="fa fa-search me-1 opacity-50"></i>
                        Buscar inscripciones
                    </button>
                </div>

            </form>
        

            <!-- Tabla -->
            <table class="table table-vcenter mt-4">
                <tr>
                    <thead class="text-center">
                        <th>Formulario</th>
                        <th>Participante</th>
                        <th>Convenio</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Fecha max. legalización</th>
                        <th></th>
                    </thead>
                </tr>
                @forelse ($paginate->Records() as $f)
                <tr class="fs-xs">
                    <td>{{ $f->getNumero() }}</td>
                    <td>
                        {{ $f->getParticipanteNombreCompleto() }}<br>
                        {{ $f->getParticipanteTipoYDocumento() }}
                    </td>
                    <td>
                        @if ($f->tieneConvenio()) 
                            {{ $f->getConvenioNombre() }} 
                        @else
                            N/A
                        @endif   
                    </td>
                    <td class="text-center">
                        {{ $f->getGrupoNombreCurso() }}. <br>
                        G{{ $f->getGrupoId() }} - {{ $f->getGrupoDia() . " / ". $f->getGrupoJornada() }} <br>
                        Salón {{ $f->getGrupoSalon() }}
                    </td>
                    <td class="texte-center">
                        @if ($f->tieneConvenio() && $f->tipoConvenioCooperativa()) 
                            {{ $f->getConvenioNombre() }} 
                        @else
                            {{ $f->getEstado() }} 
                        @endif            
                    </td>
                    <td>{{ $f->getFechaMaxLegalizacion() }}</td>
                    <td class="text-center">
                    @if ($calendario->esVigente())
                        
                    
                    @if ($f->PendienteDePago() || $f->RevisarComprobanteDePago())
                        <a href="{{ route('formularios.edit-legalizar-inscripcion', [$f->getNumero()]) }}" 
                                class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                data-bs-toggle="tooltip" 
                                title="Legalizar inscripción">
                                Legalizar
                        </a>
                        <form method="POST" action="{{ route('formularios.anular-inscripcion', [$f->getNumero(), $f->getParticipanteId()]) }}" id="form-del-anular-{{$f->getNumero()}}">
                            @csrf @method('patch')
                            <button class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning"
                                    data-bs-toggle="tooltip" 
                                    title="Anular" 
                                    type="button"
                                    data-id="{{ $f->getNumero() }}"
                                    onclick="confirmAnular(this)">
                                Anular
                            </button>
                        </form>                                             
                    @endif

                    @endif
                        @if (!$f->Anulado())
                        <a href="{{ route('formulario-inscripcion.descargar-recibo-matricula', $f->getParticipanteId()) }}" 
                                    class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                    data-bs-toggle="tooltip" 
                                    title="Descargar recibo matrícula">
                                    Recibo
                            </a>
                            @endif
                        <a href="{{ route('formularios.ver-detalle-inscripcion', $f->getNumero()) }}" 
                                    class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                                    data-bs-toggle="tooltip" 
                                    title="Detalle de la inscripción">
                                    Ver
                        </a>                            
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="10">No hay formularios para mostrar</td>
                </tr>
                @endforelse 
            </table>
            <!-- Fin tabla -->

            @include('paginator', ['route'=>$route, 'criterio' => $criterio, 'page' => $page])

    </div>

</div>


<script>
function confirmAnular(button) {
    const numeroFormulario = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-anular-${numeroFormulario}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection