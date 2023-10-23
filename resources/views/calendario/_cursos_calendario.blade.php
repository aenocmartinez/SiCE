<table class="table table-vcenter">
    <tbody>
        @forelse ($cursosCalendario as $index => $cursoCalendario)            
            <tr>
                <td class="text-start">
                    <div class="row">
                        <div class="mb-2 col-10">
                            <a href="#">{{ $cursoCalendario->getNombreCurso() }}</a>
                            <div class="input-group mt-1" style="font-size: 14px;">                                  
                                <span style="width: 100%;" class="p-1" data-bs-toggle="tooltip" title="Costo">
                                    <i class="fa fa-fw fa-sack-dollar"></i> {{  number_format($cursoCalendario->getCosto(), 0, ',', '.') }} COP
                                </span>
                                <span style="width: 100%;" class="p-1" data-bs-toggle="tooltip" title="Cupos">
                                    <i class="fa fa-fw fa-users"></i> {{  $cursoCalendario->getCupo() }}
                                </span>
                                <span style="width: 100%;" class="p-1" data-bs-toggle="tooltip" title="Modalidad">
                                    <i class="fa fa-fw si si-paper-plane"></i> {{ $cursoCalendario->getModalidad() }}
                                </span>

                            </div>

                        </div>
                        <div class="mb-2 col-2">
                            <div class="btn-group">
                                <form method="POST" action="{{ route('calendario.retirar_curso', [$cursoCalendario->getCalendarioId(), $cursoCalendario->getId(), $area_id]) }}" id="form-remove-curso-calendario-{{$cursoCalendario->getId()}}">                                                                        
                                @csrf @method('delete')
                                    <button type="button" 
                                            class="btn btn-sm btn-alt-secondary" 
                                            data-bs-toggle="tooltip" 
                                            title="Agregar a cursos abiertos"
                                            data-id="{{ $cursoCalendario->getId() }}"
                                            onclick="confirmDelete(this)"
                                            >
                                        <i class="fa fa-fw fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>                        
                    </div>
                </td>
            </tr>
        @empty
        <tr>
                <td class="text-center">                    
                    No tiene cursos asignados de esta área
                </td>
            </tr>
        @endforelse

    </tbody>
</table>

<script>
function confirmDelete(button) {
    const cursoCalendarioId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`form-remove-curso-calendario-${cursoCalendarioId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>