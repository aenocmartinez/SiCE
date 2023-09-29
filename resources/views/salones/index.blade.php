@extends("plantillas.principal")

@section("title", "Salones")
@section("description", "Listado de salones para cursos de extensión")

@section("content")

@php
$criterio = isset($criterio) ? $criterio : '';
@endphp

<div class="row">
    <div class="col-lg-6">
    <form method="post" action="{{ route('salones.buscador') }}">
        @csrf
        <div class="pt-1">
            <div class="input-group">                
                    <button class="btn btn-alt-primary">
                        <i class="fa fa-search me-1 opacity-50"></i> Buscar
                    </button>
                    <input type="text" class="form-control form-control-alt" 
                                        id="criterio" 
                                        name="criterio" 
                                        value="{{ $criterio }}"
                                        placeholder="Nombre, capacidad">                
            </div>
        </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12" style="text-align: right;">
        <a href="{{ route('salones.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear salón
        </a>
    </div>
</div>

<br>

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                @forelse ($salones as $salon)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                    <h4>{{ $salon['nombre'] }}</h4>
                    <small>Capacidad: {{ $salon['capacidad'] }}</small><br> 
                    <small>Estado: {{ $salon['esta_disponible'] ? 'disponible' : 'no disponible' }}</small> 
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('salones.edit', $salon['id']) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar salón">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('salones.delete', $salon['id']) }}" id="form-del-salon-{{$salon['id']}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar salón" 
                                        type="button"
                                        data-id="{{ $salon['id'] }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-times"></i>
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