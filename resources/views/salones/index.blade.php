@extends("plantillas.principal")

@section("title", "Salones")
@section("description", "Listado de salones para cursos de extensión")

@section("content")

@php
$criterio = isset($criterio) ? $criterio : '';
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
                @forelse ($salones as $salon)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                    <h4 class="fw-normal mb-0">{{ $salon->getNombre() }}</h4>
                    <small>
                        Capacidad: {{ $salon->getCapacidad() }}<br> 
                        Estado: {{ $salon->getDisponibleTexto() }}
                    </small> 
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('salones.edit', $salon->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar salón">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('salones.delete', $salon->getId()) }}" id="form-del-salon-{{ $salon->getId() }}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar salón" 
                                        type="button"
                                        data-id="{{ $salon->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
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