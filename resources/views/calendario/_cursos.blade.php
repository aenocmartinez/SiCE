<!-- Formulario único que envuelve todos los cursos -->
<form id="guardar-cursos-form" action="{{ route('calendario.agregar_varios_cursos') }}" method="POST" onsubmit="prepareDataBeforeSubmit(event)">
    @csrf
    <div class="curso-item mb-3">
        @foreach ($cursos as $index => $curso)            
            @php
                $idForm = $curso->getId() . $calendario_id;
            @endphp 

            <!-- Contenedor de cada curso -->
            <div class="mb-3 p-2 bg-white border-bottom d-flex align-items-center justify-content-between">
                <!-- Nombre del curso -->
                <div style="width: 45%;">
                    <span class="text-dark fs-xs fw-100 me-2">{{ $curso->getNombre() }}</span>
                </div>

                <!-- Campos de Costo y Modalidad para cada curso -->
                <input type="hidden" name="cursos[{{ $index }}][calendario_id]" value="{{ $calendario_id }}">
                <input type="hidden" name="cursos[{{ $index }}][curso_id]" value="{{ $curso->getId() }}">
                <input type="hidden" name="cursos[{{ $index }}][area_id]" value="{{ $area_id }}">

                <div class="d-flex align-items-center" style="width: 65%;">
                    <!-- Campo de Costo -->
                    <div class="input-group me-2" style="width: 45%;">
                        <span class="input-group-text fs-xs" style="font-size: 13px;">Costo</span>
                        <input type="text" class="form-control text-center" 
                               style="font-size: 13px;" 
                               name="cursos[{{ $index }}][costo]" 
                               id="costo_{{ $idForm }}" 
                               oninput="formatCurrency('{{ $idForm }}')">
                    </div>

                    <!-- Selector de Modalidad -->
                    <select class="form-select me-2 fs-xs" style="font-size: 13px; width: 45%;" name="cursos[{{ $index }}][modalidad]">
                        <option value="Presencial">Presencial</option>
                        <option value="Virtual">Virtual</option>
                    </select>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Botón único de Guardar -->
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary fw-light">
            Abrir cursos
        </button>
    </div>
</form>

<script>
function formatCurrency(idForm) {
    const input = document.getElementById(`costo_${idForm}`);
    let value = input.value.replace(/[^\d]/g, ''); // Elimina caracteres no numéricos
    if (value) {
        value = parseInt(value).toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP',
            maximumFractionDigits: 0
        });
    }
    input.value = value;
}

// Elimina el formato de moneda antes de enviar el formulario
function prepareDataBeforeSubmit(event) {
    event.preventDefault(); // Evita el envío inmediato del formulario

    const inputs = document.querySelectorAll("input[name*='[costo]']");
    inputs.forEach(input => {
        input.value = input.value.replace(/[^\d]/g, ''); // Elimina el formato
    });

    // Envía el formulario después de limpiar los valores de costo
    document.getElementById("guardar-cursos-form").submit();
}
</script>
