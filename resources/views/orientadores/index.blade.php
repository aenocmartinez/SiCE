@extends("plantillas.principal")

@section("title", "Módulo de orientadores")
@section("description", "Listado y administración de orientadores vinculados a cursos de extensión.")

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
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear orientador
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
                    <td class="fs-sm" style="width: 95%;">
                    <h4 class="fw-normal mb-0">{{ $orientador->getNombre() }}</h4>
                    <small>
                        {{ $orientador->getTipoNumeroDocumento() }} <br>
                        Nivel educativo: {{ $orientador->getNivelEducativo() }} <br>
                        Áreas: {{ $orientador->nombreAreasPertenezco() }}
                    </small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('orientadores.edit', $orientador->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar orientador">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>

                            <a href="{{ route('orientadores.moreInfo', $orientador->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="más información">
                            <i class="fa fa-fw fa-circle-info"></i>
                            </a>

                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay orientadores para mostrar</td>
                </tr>
                @endforelse 
            </table>

            @include('paginator', ['route'=>$route, 'criterio' => $criterio, 'page' => $page])
            
        </div>
    </div>
</div>


@endsection