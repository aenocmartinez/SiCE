@extends("plantillas.principal")

@section("title", "Periodo académico")
<!-- @section("description", "Listado y administración de las áreas para la gestión de inscripción a cursos de extensión.") -->

@section("content")

@if (!\Src\domain\Calendario::existeCalendarioVigente())    
    <div class="row mb-3">
        <div class="d-flex justify-content-end">
            <a href="{{ route('calendario.create') }}" class="btn btn-lg btn-info">
                <i class="fa fa-circle-plus me-1 opacity-50"></i> Crear periodo
            </a>
        </div>
    </div>        
@endif

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">

            <table class="table table-vcenter">
                @forelse ($calendarios as $calendario)
                <tr>
                    <td class="fs-sm" style="width: 20%;">                        
                        <h4 class="fw-normal mb-0">{{ $calendario->getNombre() }}</h4>
                        <small class="fw-light">
                            {{ $calendario->getFechaInicio() }} al {{ $calendario->getFechaFinal()}} <br>
                            {{ $calendario->estado() }}
                        </small> 
                    </td>
                    <td class="d-sm-table-cell">
                        @if ($calendario->esVigente())  
                        
                            <a href="{{ route('calendario.edit', $calendario->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                <i class="fa fa-fw fa-pencil-alt"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('calendario.delete', $calendario->getId()) }}" class="d-inline-block" id="form-del-calendario-{{$calendario->getId()}}">
                                @csrf
                                @method('delete')
                                <button class="fs-xs fw-semibold py-1 px-3 btn rounded-pill btn-outline-danger" 
                                        type="button"
                                        data-id="{{ $calendario->getId() }}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i> Eliminar
                                </button>
                            </form>
                            <a href="{{ route('calendario.cursos', $calendario->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-success">
                                <i class="fa fa-fw fa-book-open"></i> Abrir curso
                            </a>
                            <a href="{{ route('notificacion.periodo', $calendario->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-primary">
                                <i class="fa fa-fw fa-envelope"></i> Notificaciones
                            </a>                            
                            <a href="{{ route('calendario.cerrar', $calendario->getId()) }}" 
                                class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-warning cerrar-periodo">
                                <i class="fa fa-fw fa-calendar-check"></i> Cerrar Periodo
                            </a>
                            @endif
                            <!-- Recalcular valor a pagar -->
                             @if (Auth::user()->esSuperAdmin())
                            <a href="{{ route('calendario.recalcular-valor-a-pagar', $calendario->getId()) }}" 
                                class="fs-xs 
                                        fw-semibold 
                                        d-inline-block 
                                        py-1 
                                        px-3 
                                        btn 
                                        rounded-pill 
                                        btn-outline-danger"
                                        data-bs-toggle="tooltip" 
                                        title="Recalcular valor a pagar"
                                        >
                                <i class="fa fa-fw fa-rotate-right"></i>
                            </a>
                            @endif

                        <a href="{{ route('calendario.estadisticas', $calendario->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                            <i class="fa fa-fw fa-chart-pie"></i> Estadísticas
                        </a>
                        
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

document.querySelector('.cerrar-periodo').addEventListener('click', function(event) {
    event.preventDefault(); // Previene que se ejecute el enlace inmediatamente
    const href = this.getAttribute('href'); // Obtiene el enlace del elemento actual

    Swal.fire({
        title: '¿Confirmas el cierre del periodo?',
        html: `
            <p>Al confirmar el cierre, se aplicarán los siguientes cambios en el sistema:</p>
            <ul style="text-align: left;">
                <li>Se facturarán los convenios aplicando el porcentaje de descuento correspondiente. <strong>Se recomienda revisar los datos antes de proceder.</strong></li>
                <li>No se permitirán nuevas inscripciones.</li>
                <li>No se podrán modificar los grupos existentes.</li>
                <li>No será posible cambiar las fechas del periodo.</li>
                <li>Los datos del dashboard no estarán disponibles para consulta; toda la información estará accesible desde el botón de estadísticas del calendario.</li>
            </ul>
            <p>¿Deseas continuar?</p>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href; // Redirige al enlace original
        }
    });
});

</script>

@endsection