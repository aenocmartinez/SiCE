@extends("plantillas.principal")

@section("title", "Periodo académico")
<!-- @section("description", "Listado y administración de las áreas para la gestión de inscripción a cursos de extensión.") -->

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('calendario.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear periodo
        </a>
    </div>
</div>        

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($calendarios as $calendario)
                <tr>
                    <td class="fs-sm" style="width: 95%;">                        
                        <h4 class="fw-normal mb-0">{{ $calendario->getNombre() }}</h4>
                        <small class="fw-light">
                            {{ $calendario->getFechaInicio() }} al {{ $calendario->getFechaFinal()}} <br>
                            {{ $calendario->estado() }}
                        </small> 
                    </td>
                    <td class="text-center">
                        <div class="btn-group">

                        @if ($calendario->esVigente())                        
                            <a href="{{ route('calendario.edit', $calendario->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar periodo">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <a href="{{ route('calendario.cursos', $calendario->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Gestionar cursos del periodo">
                                <i class="fa fa-fw fa-arrow-up-right-dots"></i>
                            </a>                            
                            <form method="POST" action="{{ route('calendario.delete', $calendario->getId()) }}" id="form-del-calendario-{{$calendario->getId()}}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar periodo" 
                                        type="button"
                                        data-id="{{ $calendario->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        @endif

                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay calendario académico para mostrar</td>
                </tr>
                @endforelse 
            </table>     

        </div>
    </div>
</div>



<script>
function confirmDelete(button) {
    const calendarioId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-calendario-${calendarioId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection