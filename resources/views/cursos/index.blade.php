@extends("plantillas.principal")

@section("title", "Cursos")
@section("description", "Listado de cursos")

@section("content")

<div class="row">
    <div class="col-12" style="text-align: right;">
        <a href="{{ route('cursos.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear curso
        </a>
    </div>
</div>

<br>

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                @forelse ($cursos as $curso)
                <tr>
                    <td class="fs-sm" style="width: 95%;">
                    <h4>{{ $curso['nombre'] }}</h4>
                    <small>{{ $curso['area']['nombre'] }}</small><br> 
                    <small>{{ $curso['modalidad'] }}</small> 
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('cursos.edit', $curso['id']) }}" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="editar curso">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <form method="post" action="{{ route('cursos.delete', ['id' => $curso['id']]) }}" id="form-del-area" onclick="return deleteCurso();">
                                @csrf @method('delete')
                                <button type="button"                                        
                                        class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="eliminar curso"
                                        ><i class="fa fa-fw fa-times"></i>
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
    function deleteCurso() {
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