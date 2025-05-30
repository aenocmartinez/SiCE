@extends("plantillas.principal")

@section("title", "Participantes")
@section("description", "")

@section("content")


@php
    $criterio = isset($criterio) ? $criterio : '';
    $route = "participantes.index";
    $page = 1;
    if (strlen($criterio)>0) {
        $route = "participantes.buscador-paginador";
    }
@endphp

<div class="row mb-3">

    <div class="row">
        
        <div class="col-lg-8 col-sm-12">
            <form method="post" action="{{ route('participantes.buscador') }}">
                @csrf
                <div class="pt-0">
                    <div class="input-group">                
                        <button class="btn btn-alt-primary" type="submit">
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

        <div class="col-lg-4 col-sm-12 col-xs-12 text-end">
            <a href="{{ route('participantes.create') }}" class="btn btn-lg btn-info">
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear participante
            </a>
        </div>        

    </div>
</div>        

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                <tr>
                    <thead>
                        <th width="35%">Nombre</th>
                        <th width="15%">Documento</th>
                        <th width="15%">Email</th>
                        <th width="10%">Teléfono</th>
                        <th width="25%"></th>
                    </thead>
                </tr>                
                @forelse ($paginate->Records() as $p)
                <tr class="fs-xs">
                    <td>{{ $p->getNombreCompleto() }}</td>
                    <td>{{ $p->getDocumentoCompleto() }}</td>
                    <td>{{ $p->getEmail() }}</td>
                    <td>{{ $p->getTelefono() }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1 flex-wrap">
                            <a href="{{ route('participantes.edit', $p->getId()) }}" 
                            class="fs-xs fw-semibold btn rounded-pill btn-outline-secondary"
                            data-bs-toggle="tooltip" 
                            title="Editar">
                                <i class="fa fa-fw fa-pencil-alt"></i> 
                            </a>
                            <a href="{{ route('participantes.formularios', $p->getId()) }}" 
                            class="fs-xs fw-semibold btn rounded-pill btn-outline-success"
                            data-bs-toggle="tooltip" 
                            title="Inscripciones">
                                <i class="fa fa-fw fa-address-card"></i> 
                            </a>
                            <a href="{{ route('participantes.descargar-certificados', $p->getId()) }}" 
                            class="fs-xs fw-semibold btn rounded-pill btn-outline-info"
                            data-bs-toggle="tooltip" 
                            title="Certificados">
                                <i class="fa fa-fw fa-file-circle-check"></i> 
                            </a>
                        </div>
                    </td>                  
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="10">No hay participantes para mostrar</td>
                </tr>
                @endforelse 
            </table>     
            @include('paginator', ['route'=>$route, 'criterio' => $criterio, 'page' => $page])
        </div>
    </div>
</div>



<script>
function confirmDelete(button) {
    const participanteId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-participante-${participanteId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection