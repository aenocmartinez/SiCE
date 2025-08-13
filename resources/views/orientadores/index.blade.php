@extends("plantillas.principal")

@section("title", "Módulo de instructores")
@section("description", "Listado y administración de instructores vinculados a cursos de extensión.")

@section("content")

@php
    $criterio = isset($criterio) ? $criterio : '';
    $route = "orientadores.index";
    $page = 1;
    if (strlen($criterio)>0) {
        $route = "orientadores.buscador-paginador";
    }
@endphp

<div class="row mb-3">

    <div class="row">
        
        <div class="col-lg-8 col-sm-12">
            <form method="post" action="{{ route('orientadores.buscador') }}">
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
        
        <div class="col-lg-4 col-sm-12 col-xs-12" style="text-align: right;">
            <a href="{{ route('orientadores.create') }}" class="btn btn-lg btn-info">
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear instructor
            </a>
        </div>

    </div>
</div>


<div class="row">
    <div class="block block-rounded">
        <div class="block-content">


            <table class="table table-vcenter">
                @forelse ($paginate->Records() as $orientador)
                <tr>
                    <td class="fs-sm" style="width: 72%;">
                    <h4 class="fw-normal mb-0">{{ $orientador->getNombre() }}</h4>
                    <small>
                    {{ $orientador->getEmailPersonal() }} <br>
                        {{ $orientador->getTipoNumeroDocumento() }}
                        <!-- Nivel educativo: {{ $orientador->getNivelEducativo() }} <br> -->                        
                        <!-- Áreas: {{ $orientador->nombreAreasPertenezco() }} -->
                    </small>
                    </td>
                    <td class="text-center">
                        <div class="d-sm-table-cell">
                            <a href="{{ route('orientadores.edit', $orientador->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                <i class="fa fa-fw fa-pencil-alt"></i> Editar
                            </a>
                            <a href="{{ route('orientadores.moreInfo', $orientador->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                <i class="fa fa-fw fa-circle-info"></i> Más información
                            </a>                            
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay instructores para mostrar</td>
                </tr>
                @endforelse 
            </table>

            @include('paginator', ['route'=>$route, 'criterio' => $criterio, 'page' => $page])
            
        </div>
    </div>
</div>


@endsection