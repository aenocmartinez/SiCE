@extends("plantillas.principal")

@section("title", "Convenios")
@section("description", "Listado de convenios")

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Combobox para seleccionar periodo -->
        <div>
            <select class="form-select form-select-lg" id="select-periodo" onchange="filtrarPorPeriodo(this.value)">
                <option value="">Seleccione un periodo</option>
                @foreach ($periodos as $periodo)
                    <option value="{{ $periodo->getId() }}" {{ isset($periodoSeleccionado) && $periodoSeleccionado == $periodo->getId() ? 'selected' : '' }}>
                        {{ $periodo->getNombre() }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Botón Crear Convenio -->
        <div>
            <a href="{{ route('convenios.create') }}" class="btn btn-lg btn-info">
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear convenio
            </a>
        </div>
    </div>
</div>       

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($convenios as $convenio)
                <tr>
                    <td class="fs-sm" style="width: 49%;">
                    <h4 class="fw-normal mb-0">{{ $convenio->getNombre() }} - {{ $convenio->getNombreCalendario() }}</h4>
                        <small class="fw-light">
                         
                            @if ($convenio->esCooperativa())                                
                                Es una cooperativa <br>
                            @endif
                            Descuento: {{ $convenio->getDescuento()."%" }}<br>
                            {{ $convenio->getVigenciaEnTexto()}}
                        </small>                     
                    </td>
                    <td class="text-center">
                        <div class="d-sm-table-cell">
                            @if ($convenio->esVigente())
                                <a href="{{ route('convenios.edit', $convenio->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                    <i class="fa fa-fw fa-pencil-alt"></i> Editar
                                </a>  

                                @if (!$convenio->esUCMC()) 
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
                                @endif
                            @endif
                            <a href="{{ route('convenios.mas-info', $convenio->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                                <i class="fa fa-fw fa-circle-info"></i> Más info
                            </a>   
                            @if (!$convenio->tieneBeneficiariosPotenciales() && !$convenio->haSidoFacturado() && $convenio->esVigente())                                
                            <a href="{{ route('convenios.beneficiarios', $convenio->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-warning">
                                <i class="fa fa-fw fa-file-import"></i> Cargar beneficiados
                            </a>                                                                  
                            @endif                           
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

function filtrarPorPeriodo(periodoId) 
{

    if (periodoId) 
    {        
        const url = `/periodos/${periodoId}/convenios`;
        window.location.href = url;
    } 
    else 
    {    
        const url = '/convenios';
        window.location.href = url;
    }
}


</script>

@endsection
