@extends("plantillas.principal")

@section("title", "Listado de inscripciones")
@section("description", "Listado de participantes inscritos en el periodo académico actual.")

@section("content")

<div class="row">

    <div class="block block-rounded block-content">
       

            <!-- Tabla -->
            <table class="table table-vcenter mt-4">
                <tr>
                    <thead class="text-center">
                        <th>Formulario</th>
                        <th>Participante</th>
                        <th>Convenio</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th></th>
                        <th></th>
                    </thead>
                </tr>
                @forelse ($formularios as $f)
                <tr class="fs-xs">
                    <td>{{ $f->getNumero() }}</td>
                    <td>
                        {{ $f->getParticipanteNombreCompleto() }}
                        <br>
                        {{ $f->getParticipanteTipoYDocumento() }}
                    </td>
                    <td>
                        @if ($f->tieneConvenio()) 
                            {{ $f->getConvenioNombre() }} 
                        @else
                            N/A
                        @endif                          
                    </td>
                    <td class="text-center">
                        {{ $f->getGrupoNombreCurso() }}. <br>
                        G{{ $f->getGrupoId() }} - {{ $f->getGrupoDia() . " / ". $f->getGrupoJornada() }} <br>
                        Salón {{ $f->getGrupoSalon() }}
                    </td>
                    <td class="texte-center">
                        @if ($f->tieneConvenio() && $f->tipoConvenioCooperativa()) 
                            {{ $f->getConvenioNombre() }} 
                        @else
                            {{ $f->getEstado() }} 
                        @endif
                    </td>
                    <td>${{ number_format($f->getTotalAPagar(), 0, ',', '.') }} COP</td>
                    <td class="text-center">
                    
                        
                    
                    @if ($f->PendienteDePago() || $f->RevisarComprobanteDePago())
                        <a href="{{ route('formularios.edit-legalizar-inscripcion', [$f->getNumero()]) }}" 
                                class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                data-bs-toggle="tooltip" 
                                title="Legalizar inscripción">
                                Legalizar
                        </a>
                        <form method="POST" action="{{ route('formularios.anular-inscripcion', [$f->getNumero(), $f->getParticipanteId()]) }}" id="form-del-anular-{{$f->getNumero()}}">
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
                    @endif

                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center" colspan="10">No hay formularios para mostrar</td>
                </tr>
                @endforelse 
            </table>
            <!-- Fin tabla -->

    </div>

</div>



<script>
function confirmAnular(button) {
    const numeroFormulario = button.getAttribute('data-id');

    Swal.fire({
        title: '¿Estás seguro de anular la inscripción?',
        input: 'textarea',
        inputLabel: 'Motivo de anulación',
        inputPlaceholder: 'Escribe aquí el motivo...',
        inputAttributes: {
            'aria-label': 'Motivo de anulación'
        },
        showCancelButton: true,
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value.trim()) {
                return 'Debes indicar un motivo';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-del-anular-${numeroFormulario}`);
            if (form) {
                const motivoInput = document.createElement('input');
                motivoInput.type = 'hidden';
                motivoInput.name = 'motivo_anulacion';
                motivoInput.value = result.value.trim();
                form.appendChild(motivoInput);
                form.submit();
            }
        }
    });
}
</script>


@endsection