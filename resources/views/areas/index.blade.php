@extends("plantillas.principal")

@section("title", "Módulo de áreas")
@section("description", "Listado y administración de las áreas para la gestión de inscripción a cursos de extensión.")

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('areas.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear área
        </a>
    </div>
</div>        

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($areas as $area)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                        {{ $area['nombre'] }}
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('areas.edit', $area['id']) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar área">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('areas.delete', $area['id']) }}" id="form-del-area-{{$area['id']}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar área" 
                                        type="button"
                                        data-id="{{ $area['id'] }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay áreas para mostrar</td>
                </tr>
                @endforelse 
            </table>     

        </div>
    </div>
</div>



<script>
function confirmDelete(button) {
    const areaId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-area-${areaId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection