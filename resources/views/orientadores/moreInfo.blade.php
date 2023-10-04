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
            <div class="col-12">
                <h4 class="fw-light">
                    {{ $orientador->getNombre() }} <br>
                    <small>{{ $orientador->getTipoNumeroDocumento() }}</small>
                </h4>
                <h5 class="fw-light">
                    <small>                                                
                        <i class="fa fa-fw fa-envelope"></i> 
                            {{ $orientador->getEmailPersonal() }} 
                            {{ !empty($orientador->getEmailInstitucional()) ? '/ ' . $orientador->getEmailInstitucional() : '' }}
                        <br>
                        <i class="fa fa-fw fa-address-book"></i> {{ $orientador->getDireccion() }} <br>
                        <i class="fa fa-fw fa-arrows-spin"></i> {{ $orientador->getEps() }} <br>
                    </small>                    
                </h5>

            </div>

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
                        @foreach ($orientador->misAreas() as $area)
                        <a class="list-group-item list-group-item-action" href="javascript:void(0)">
                        <small>{{ $area->getNombre() }}</small>
                        </a>
                        @endforeach                    
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-6 mt-4">
                <div class="block block-rounded h-100 mb-0">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between bg-primary-dark">
                    <div class="me-3">
                        <p class="fs-sm fw-medium text-white-75 mb-0">
                        Últimos cursos dictados 
                        </p>
                    </div>
                    </div>
                    <div class="list-group push">
                        @foreach ($orientador->misGrupos() as $grupo)
                        <a class="list-group-item list-group-item-action" href="javascript:void(0)">
                        <small>
                            {{ $grupo->getCurso()->getNombre() }} / Periodo: {{ $grupo->getCalendario()->getNombre() }}
                            <span class="text-muted" style="font-size: 11px;">
                                ({{ $grupo->getDia() }} en la {{ strtolower($grupo->getJornada()) }})
                            </span>
                        </small>
                        </a>
                        @endforeach                    
                    </div>
                </div>
            </div>            

        </div>
    </div>
</div>     

@endsection