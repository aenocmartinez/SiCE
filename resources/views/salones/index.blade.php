@extends("plantillas.principal")

@section("title", "Salones")
@section("description", "Listado de salones para cursos de extensión")

@section("content")

@php
    $criterio = isset($criterio) ? $criterio : '';
    $route = "salones.index";
    $page = 1;
    if (strlen($criterio)>0) {
        $route = "salones.buscador-paginador";
    }
@endphp

<div class="row mb-3">

    <div class="row">
        
        <div class="col-lg-8 col-sm-12">
            <form method="post" action="{{ route('salones.buscador') }}">
                @csrf
                <div class="pt-0">
                    <div class="input-group">                
                        <button class="btn btn-alt-primary">
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
            <a href="{{ route('salones.create') }}" class="btn btn-lg btn-info">
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear salón
            </a>
        </div>

    </div>
</div>

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                @forelse ($paginate->Records() as $salon)
                <tr>
                    <td class="fs-sm" style="width: 78%;">
                    <h4 class="fw-normal mb-0">{{ $salon->getNombreYTipoSalon() }}</h4>
                    <small>
                        Capacidad: {{ $salon->getCapacidad() }}<br> 
                        Estado: {{ $salon->getDisponibleTexto() }}
                    </small> 
                    </td>
                    <td class="text-center">
                        <div class="d-sm-table-cell">
                            <a href="{{ route('salones.edit', $salon->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                <i class="fa fa-fw fa-pencil-alt"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('salones.delete', $salon->getId()) }}" class="d-inline-block" id="form-del-salon-{{ $salon->getId() }}">
                                @csrf
                                @method('delete')
                                <button class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-danger" 
                                        type="button"
                                        data-id="{{ $salon->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i> Eliminar
                                </button>
                            </form>                                                        
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay salones para mostrar</td>
                </tr>
                @endforelse 
            </table>

            @include('paginator', ['route'=>$route, 'criterio' => $criterio, 'page' => $page])
            
        </div>
    </div>
</div>


<script>
function confirmDelete(button) {
    const salonId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-salon-${salonId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection