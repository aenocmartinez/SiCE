@extends("plantillas.principal")

@section("title", "Orientadores")
@section("description", "Listado y administración de orientadores de cursos de extensión.")

@section("content")

@php
$criterio = isset($criterio) ? $criterio : '';
@endphp

<div class="row">
    <div class="col-lg-6">
    <form method="post" action="{{ route('orientadores.buscador') }}">
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
                                        placeholder="Nombre, documento, correo electrónico">                
            </div>
        </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12" style="text-align: right;">
        <a href="{{ route('orientadores.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear orientador
        </a>
    </div>
</div>

<br>

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                @forelse ($orientadores as $o)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                    <h4>{{ $o['nombre'] }}</h4>
                    <small>( {{ $o['tipo_documento'] .". " . $o['documento'] . " / " . ($o['estado'] ? 'Activo':'Inactivo')}} )</small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('orientadores.edit', $o['id']) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar orientador">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('orientadores.delete', $o['id']) }}" id="form-del-orientador-{{$o['id']}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar orientador" 
                                        type="button"
                                        data-id="{{ $o['id'] }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay orientadores para mostrar</td>
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
            const form = document.getElementById(`form-del-orientador-${salonId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection