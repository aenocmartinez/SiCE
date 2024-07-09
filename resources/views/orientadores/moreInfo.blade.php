@extends("plantillas.principal")

@php
    $titulo = "+ información del orientador";
@endphp

@section("title", $titulo)

@section("seccion")
    <a class="link-fx" href="{{ route('orientadores.index') }}">
        Orientadores
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">
            <div class="col-6">
                <h4 class="fw-light">
                    {{ $orientador->getNombre() }} <br>
                    <small>
                        {{ $orientador->getTipoNumeroDocumento() }} 
                        <a href="{{ route('orientadores.edit', $orientador->getId()) }}">(editar)</a>
                    </small>
                </h4>
                <h5 class="fw-light">
                    <small>                                                
                        <i class="fa fa-fw fa-envelope"></i> 
                            {{ $orientador->getEmailPersonal() }} 
                            {{ !empty($orientador->getEmailInstitucional()) ? '/ ' . $orientador->getEmailInstitucional() : '' }}
                        <br>
                        <i class="fa fa-fw fa-address-book"></i> {{ $orientador->getDireccion() }} <br>
                        <i class="fa fa-fw fa-arrows-spin"></i> {{ $orientador->getEps() }} <br>
                        <i class="fa fa-fw fa-calendar-check"></i> {{ $orientador->getFechaNacimientoFormateada() }} <br>
                    </small>                    
                </h5>

            </div>
            <div class="col-6">
                <h5 class="fw-light">
                        <small>                                                
                            <i class="fa fa-fw fa-user-graduate"></i> 
                                {{ $orientador->getNivelEducativo() }} 
                            <br>
                            <i class="fa fa-fw fa-money-check-dollar"></i> Rango salarial: {{ $orientador->getRangoSalarial() }} <br><br>
                            <i class="fa fa-fw fa-chalkboard-user"></i> <br>
                            <p>
                            {{ $orientador->getObservacion() }}
                            </p>
                        </small>                    
                    </h5>                
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-xl-6 mt-4">
                <div class="block block-rounded h-100 mb-0">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between bg-info">
                    <div class="me-3">
                        <p class="fs-sm fw-medium text-white-75 mb-0">
                        Áreas a las que pertenece
                        </p>
                    </div>
                    </div>
                    <div class="list-group push">
                        @forelse ($orientador->misAreas() as $area)
                        <a class="list-group-item list-group-item-action" href="javascript:void(0)">
                        <small>{{ $area->getNombre() }}</small>
                        </a>
                        @empty
                        <a class="list-group-item list-group-item-action" href="javascript:void(0)">
                            <small>No tiene áreas asignadas</small>
                        </a>                        
                        @endforelse                    
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-6 mt-4">
                <div class="block block-rounded h-100 mb-0">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between bg-primary-dark">
                    <div class="me-3">
                        <p class="fs-sm fw-medium text-white-75 mb-0">
                        {{ count($orientador->misGrupos()) }} Cursos en el periodo vigente
                        </p>                        
                    </div>
                    </div>
                    <div class="list-group push">
                        @forelse ($orientador->misGrupos() as $grupo)
                        <div class="list-group-item list-group-item-action text-center">
                            <small>
                                <span class="fw-bold text-muted">{{ $grupo->getNombreCurso() }}</span>
                                <span class="text-muted fs-xs">
                                ({{ $grupo->getNombre() }} - {{ $grupo->getDia() }} en la {{ strtolower($grupo->getJornada()) }} )
                                </span>
                            </small>

                            <div class="fs-xs fw-bold">

                                <!-- Pie Chart Container -->
                                <div class="js-pie-chart pie-chart fw-bold mb-1 mt-1" 
                                     data-percent="{{ round(($grupo->getTotalInscritos() / $grupo->getCupo()) * 100) }}" 
                                     data-line-width="3" 
                                     data-size="60" 
                                     data-bar-color="#82b54b" 
                                     data-track-color="#e9e9e9">
                                     <span>{{ $grupo->getTotalInscritos() }}/{{ $grupo->getCupo() }}</span>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('grupos.descargar-planilla-asistencia', $grupo->getId()) }}" 
                                        class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                        <i class="fa fa-fw fa-download"></i> Planilla asistencia
                                    </a>
                                    <a href="{{ route('grupos.descargar-estado-legalizacion-participantes', $grupo->getId()) }}" 
                                        class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                        <i class="fa fa-fw fa-download"></i> Legalización participantes
                                    </a>                                    
                                </div>
                            </div>
                        </div>
                        @empty
                        <a class="list-group-item list-group-item-action" href="javascript:void(0)">
                            <small>No tiene cursos asignados</small>
                        </a>                        
                        @endforelse                    
                    </div>
                </div>
            </div>            

        </div>
    </div>
</div>     

@endsection