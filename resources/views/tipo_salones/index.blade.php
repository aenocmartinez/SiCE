@extends("plantillas.principal")

@section("title", "Tipo de salones")
@section("description", "Listado de los diferentes tipos de salones para cursos de extensión")

@php
    $criterio = isset($criterio) ? $criterio : '';
@endphp

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('tipo-salones.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear tipo de salón
        </a>
    </div>
</div>    


<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                @forelse ($paginate->Records() as $tipo)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                    <h4 class="fw-normal mb-0">{{ $tipo->getNombre() }}</h4>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('tipo-salones.edit', $tipo->getId()) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar tipo salón">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="POST" action="{{ route('tipo-salones.delete', $tipo->getId()) }}" id="form-del-tipo-salon-{{ $tipo->getId() }}">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar tipo salón" 
                                        type="button"
                                        data-id="{{ $tipo->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay tipo de salones para mostrar</td>
                </tr>
                @endforelse 
            </table>

            @include('paginator',['route' => 'tipo-salones.index', 'criterio' => $criterio])
        </div>
    </div>
</div>


<script>
function confirmDelete(button) {
    const tipoSalonId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-tipo-salon-${tipoSalonId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection