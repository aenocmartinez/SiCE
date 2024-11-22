<div class="curso-item fs-xs">
    @forelse ($cursosCalendario as $index => $cursoCalendario)            
        <div class="p-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-truncate">
                    <span class="text-dark fs-xs fw-100">{{ $cursoCalendario->getNombreCurso() }}</span>
                </div>

                <form method="POST" action="{{ route('calendario.retirar_curso', [$cursoCalendario->getCalendarioId(), $cursoCalendario->getId(), $area_id]) }}" id="form-remove-curso-calendario-{{$cursoCalendario->getId()}}">                                                                        
                    @csrf @method('delete')
                    <button class="btn btn-sm btn-danger fs-xs" 
                            onclick="confirmDelete(this)" 
                            data-id="{{ $cursoCalendario->getId() }}" 
                            data-bs-toggle="tooltip" 
                            title="Retirar curso"
                            >
                            <i class="fa fa-times"></i>
                </button>                    
                </form>
                
            </div>

            <div class="row mt-2">
                <div class="col-6">
                    <span class="d-block text-muted fs-xs" data-bs-toggle="tooltip" title="Costo">
                        <i class="fa fa-sack-dollar me-1"></i>{{ number_format($cursoCalendario->getCosto(), 0, ',', '.') }} COP
                    </span>
                </div>
                <div class="col-6 text-end">
                    <span class="d-block text-muted fs-xs" data-bs-toggle="tooltip" title="Modalidad">
                        <i class="fa fa-paper-plane me-1"></i>{{ $cursoCalendario->getModalidad() }}
                    </span>
                </div>
            </div>
        </div>
    @empty
        <p class="text-center text-muted">No hay cursos abiertos en este periodo de esta área.</p>
    @endforelse
</div>

<script>
function confirmDelete(button) {
    const cursoCalendarioId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'El curso será removido del periodo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, remover',
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
