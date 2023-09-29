@extends("plantillas.principal")

@section("title", "Áreas")
@section("description", "Listado de áreas")

@section("content")
    

<div class="row">
    <div class="col-12" style="text-align: right;">
        <a href="{{ route('areas.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear área
        </a>
    </div>
</div>

<br>

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
                            <form method="post" action="{{ route('areas.delete', ['id' => $area['id']]) }}" id="form-del-area" onclick="return deleteArea();">
                                @csrf @method('delete')
                                <button type="button"                                        
                                        class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar área"
                                        ><i class="fa fa-fw fa-times"></i>
                                </button>
                            </form>
                        </div>
                    </td>                    
                    <!-- <td>
                        <div class="btn-group">
                            <form method="post" action="{{ route('areas.delete', ['id' => $area['id']]) }}">
                                @csrf @method('delete')
                                <button class="btn btn-sm rounded-pill btn-outline-danger fs-sm">eliminar</button>
                            </form>
                                                        
                            <a class="btn btn-sm rounded-pill btn-outline-secondary" href="{{ route('areas.edit', $area['id']) }}">
                                editar
                            </a>                    
                        </div>
                    </td> -->
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
    function deleteArea() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, estoy seguro',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-del-area').submit();
            }
        });
    }
    </script>

@endsection