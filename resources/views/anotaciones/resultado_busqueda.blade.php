@extends("plantillas.principal")

@section("title", "Consultar comentarios")
@section("description", "Permite buscar y visualizar los comentarios realizados sobre un formulario de inscripción durante su proceso de gestión.")

@section("content")

        <!-- Tabla -->
        <div class="table-responsive mt-4">
            <table class="table table-striped align-middle">
                <thead class="text-center bg-dark text-white">
                    <tr class="fs-xs">
                        <th class="fw-light" width="32%">Participante</th>
                        <th class="fw-light" width="10%">Número de formulario</th>
                        <th class="fw-light" width="10%">Estado</th>
                        <th class="fw-light" width="19%">Curso</th>
                        <th class="fw-light" width="24%">Comentarios</th> 
                        <th class="fw-light" width="5%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($formularios as $formulario)
                    <tr class="fs-xs">
                        <td class="text-center">
                            {{ $participante->getNombreCompleto() }}
                            <br>
                            {{ $participante->getDocumentoCompleto() }}
                        </td>
                        <td class="text-center">{{ $formulario->getNumeroFormulario() }}</td>
                        <td class="text-center">{{ $formulario->getEstadoFormulario() }}</td>
                        <td class="text-center">{{ $formulario->getCurso() }}</td>
                        <td>{{ $formulario->getComentario() }}</td>                        
                        <td>
                        <div class="d-flex">

                                    <!-- <a href="{{ route('formulario-inscripcion.descargar-recibo-matricula', $participante->getId()) }}" 
                                        class="btn btn-sm btn-outline-info rounded-pill shadow-sm me-1"
                                        data-bs-toggle="tooltip" 
                                        title="Descargar recibo matrícula">
                                        <i class="fa fa-file"></i> 
                                    </a> -->
                                    <a href="{{ route('formularios.ver-detalle-inscripcion', $formulario->getNumeroFormulario()) }}" 
                                       class="btn btn-sm btn-info"
                                       data-bs-toggle="tooltip" 
                                       title="Detalle de la inscripción">
                                       Ver más detalles
                                       <!-- <i class="fa fa-eye"></i>  -->
                                    </a>   
                                </div>                                
                        </td> 
                                    
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="7">No hay formularios para mostrar</td>
                    </tr>
                @endforelse 
                </tbody>
            </table>
        </div>
        <!-- Fin tabla -->

@endsection
