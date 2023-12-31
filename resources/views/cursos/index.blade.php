@extends("plantillas.principal")

@section("title", "Cursos")
@section("description", "Listado de cursos")

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('cursos.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear curso
        </a>
    </div>
</div>    

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($cursos as $curso)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                    <h4 class="fw-normal mb-0">{{ $curso->getNombre() }}</h4>
                    <small>
                        {{ $curso->getArea()->getNombre() }}<br> 
                    </small> 
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('cursos.edit', $curso->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar curso">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('cursos.delete', $curso->getId()) }}" id="form-del-curso-{{$curso->getId()}}">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar curso" 
                                        type="button"
                                        data-id="{{ $curso->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay cursos para mostrar</td>
                </tr>
                @endforelse 
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(button) {
    const cursoId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-curso-${cursoId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection