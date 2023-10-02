@extends("plantillas.principal")

@section("title", "Grupos")
<!-- @section("description", "Listado y administración de las áreas para la gestión de inscripción a cursos de extensión.") -->

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('grupos.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear grupo
        </a>
    </div>
</div>        

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($grupos as $grupo)
                <tr>
                    <td class="fs-sm" style="width: 95%;">                        
                        <h4 class="fw-normal mb-0">{{ $grupo->getId() }}</h4>
                        <small class="fw-light">
                            Curso: {{ $grupo->getCurso()->getNombre() }} <br>
                            Calendario: {{ $grupo->getCalendario()->getNombre() }} <br>
                            Horario: {{ $grupo->getDia() }} / jornada {{ $grupo->getJornada() }} <br>
                            Salón: {{ $grupo->getSalon()->getNombre() }} <br>
                            Orientador: {{ $grupo->getOrientador()->getNombre() }}
                        </small> 
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('grupos.edit', $grupo->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar grupo">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('grupos.delete', $grupo->getId()) }}" id="form-del-grupo-{{$grupo->getId()}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar grupo" 
                                        type="button"
                                        data-id="{{ $grupo->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay grupos para mostrar</td>
                </tr>
                @endforelse 
            </table>     

        </div>
    </div>
</div>



<script>
function confirmDelete(button) {
    const grupoId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-grupo-${grupoId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection