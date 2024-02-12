@extends("plantillas.principal")

@php
    $titulo = "Formularios de inscripciones";
@endphp

@section("title", $titulo)
@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('participantes.index') }}">
        Participantes
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")    


<div class="row">

    <div class="block block-rounded">

        <div class="block-content p-2">
            {{ $participante->getNombreCompleto() }} <br>
            {{ $participante->getDocumentoCompleto() }}            
        </div>

    </div>

</div>


<div class="row">

    <div class="block block-rounded">

        <div class="block-content">

            <table class="table table-vcenter">
                <tr>
                    <thead class="text-center">
                        <th style="width: 3%;">Formulario</th>
                        <th style="width: 3%;">Periodo</th>
                        <th style="width: 35%;">Curso</th>
                        <th style="width: 20%;">Valor a pagar</th>
                        <th style="width: 15%;">Estado</th>
                        <th style="width: 25%;"></th>
                    </thead>
                </tr>                
                @forelse ($formularios as $f)
                <tr class="fs-xs text-center">
                    <td>{{ $f->getNumero() }}</td>
                    <td>{{ $f->getGrupoCalendarioNombre() }}</td>
                    <td>
                        <a href="#" class="fs-sm">{{ $f->getGrupoNombreCurso() }}</a>
                        <br>
                        <small>
                            G{{ $f->getGrupoId()  }}: 
                            {{ $f->getGrupoDia()  }} / {{ $f->getGrupoHora() . " " . $f->getGrupoJornada() }}<br>{{ $f->getGrupoModalidad() }}
                        </small>
                    </td>
                    <td class="text-center">{{ $f->getTotalAPagarFormateado() }}</td>
                    <td class="text-center">{{ $f->getEstado() }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            @if ($f->getEstado() == "Pendiente de pago")                            
                                <a href="{{ route('participantes.edit-legalizar-inscripcion', [$f->getNumero()]) }}" 
                                        class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                        data-bs-toggle="tooltip" 
                                        title="Legalizar inscripción">
                                        Legalizar
                                </a>
                                <form method="POST" action="{{ route('participantes.anular-inscripcion', [$f->getNumero(), $participante->getId()]) }}" id="form-del-anular-{{$f->getNumero()}}">
                                    @csrf @method('patch')
                                    <button class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-warning-light text-warning"
                                            data-bs-toggle="tooltip" 
                                            title="Anular" 
                                            type="button"
                                            data-id="{{ $f->getNumero() }}"
                                            onclick="confirmAnular(this)">
                                        Anular
                                    </button>
                                </form>
                            @else
                            Número pago: {{ $f->getVoucher() }}
                            @endif
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="10">No hay formularios para mostrar</td>
                </tr>
                @endforelse 
            </table>     

        </div>
    </div>
</div>



<script>
function confirmAnular(button) {
    const numeroFormulario = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-anular-${numeroFormulario}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>

@endsection