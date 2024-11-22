<div class="curso-item mb-3">
    @forelse ($cursos as $index => $curso)            
        @php
            $idForm = $curso->getId() . $calendario_id;
        @endphp 

        <!-- Contenedor de cada curso -->
        <div class="mb-3 p-2 bg-white border-bottom d-flex align-items-center justify-content-between">
            <!-- Nombre del curso -->
            <div style="width: 45%;">
                <span class="text-dark fs-xs fw-100 me-2">{{ $curso->getNombre() }}</span>
            </div>
            
            <div class="text-end" style="width: 65%;">

                <button 
                    type="button" 
                    class="btn btn-alt-primary fw-light btn-sm fs-xs add-course" 
                    data-id="{{ $curso->getId() }}" 
                    data-nombre="{{ $curso->getNombre() }}">
                    Agregar
                </button>
            </div>
        </div>
        @empty
        <p class="text-center text-muted">No hay registros para mostrar.</p>
    @endforelse
</div>