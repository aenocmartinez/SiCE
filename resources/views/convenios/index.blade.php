@extends("plantillas.principal")

@section("title", "Convenios")
@section("description", "Listado de convenios")

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('convenios.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear convenio
        </a>
    </div>
</div>        

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($convenios as $convenio)
                <tr>
                    <td class="fs-sm" style="width: 60%;">
                    <h4 class="fw-normal mb-0">{{ $convenio->getNombre() }} - {{ $convenio->getDescuento()."%" }} de descuento</h4>
                        <small class="fw-light">
                            {{ $convenio->getVigenciaEnTexto() }} <br>
                            Calendario: {{ $convenio->getNombreCalendario() }}<br>
                        </small>                     
                    </td>
                    <td class="text-center">
                        <div class="d-sm-table-cell">
                            <a href="{{ route('convenios.edit', $convenio->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                <i class="fa fa-fw fa-pencil-alt"></i> Editar
                            </a>  
                            <form method="POST" action="{{ route('convenios.delete', $convenio->getId()) }}" class="d-inline-block" id="form-del-convenio-{{$convenio->getId()}}">
                                @csrf
                                @method('delete')
                                <button class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-danger" 
                                        type="button"
                                        data-id="{{ $convenio->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i> Eliminar
                                </button>
                            </form> 
                            <a href="{{ route('convenios.mas-info', $convenio->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                <i class="fa fa-fw fa-circle-info"></i> Más información
                            </a>                                                                                    
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay convenios para mostrar</td>
                </tr>
                @endforelse 
            </table>     

        </div>
    </div>
</div>



<script>
function confirmDelete(button) {
    const convenioId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-convenio-${convenioId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection