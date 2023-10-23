<table class="table table-vcenter">
    <tbody>
        @foreach ($cursos as $index => $curso)            
            @php
                if ($curso->getNumeroEnCalendario() == 2) {
                    continue;
                }


                $idForm = $curso->getId() . $calendario_id;
            @endphp 
                <tr>
                    <td>
                        <form id="f-cc-{{ $idForm }}" action="{{ route('calendario.agregar_curso') }}" method="POST">
                        @csrf
                        <input type="hidden" name="calendario_id" id="calendario_id" value="{{ $calendario_id }}">
                        <input type="hidden" name="curso_id" id="curso_id" value="{{ $curso->getId() }}">
                        <input type="hidden" name="area_id" id="area_id" value="{{ $area_id }}">                        
                        <a href="#">{{ $curso->getNombre() }}</a>
                            
                            <div class="row">
                                <div class="mb-2 col-10">
                                    <div class="input-group">
                                    <span class="input-group-text" style="width: 30%; font-size: 13px;"><small>Costo</small></span>
                                    <input type="text" 
                                            class="form-control text-center" 
                                            style="font-size: 13px;"
                                            name="costo_{{ $idForm }}" 
                                            id="costo_{{ $idForm }}" 
                                            >                        
                                            <script>
                                                $(document).ready(function() {
                                                    $('#costo_{{ $idForm }}').on('input', function() {
                                                        var valor = $(this).val();
                                                        valor = valor.replace(/[^\d]/g, '');
                                                        
                                                        if (valor !== '') {
                                                            valor = parseFloat(valor);
                                                            var valorFormateado = valor.toLocaleString('es-CO', {
                                                                style: 'currency',
                                                                currency: 'COP',
                                                                maximumFractionDigits: 0 
                                                            });
                                                        } else {
                                                            valorFormateado = '';
                                                        }
    
                                                        $(this).val(valorFormateado);
                                                    });
                                                });                                     
                                            </script>                                               
                                    </div>


                                    <div class="input-group">
                                        <span class="input-group-text"  style="width: 30%; font-size: 13px;"><small>Cupos</small></span>
                                        <input type="number" 
                                                class="form-control text-center" 
                                                style="font-size: 13px;"
                                                id="cupos_{{ $idForm }}" 
                                                name="cupos_{{ $idForm }}"
                                                >                        
                                    </div>
    
                                    <div class="input-group">
                                        <span class="input-group-text"  style="width: 30%; font-size: 13px;"><small>Modalidad</small></span>
                                        <select class="form-control text-center" style="font-size: 13px;" 
                                                name="modalidad_{{ $idForm }}" 
                                                id="modalidad_{{ $idForm }}">

                                                <option value="Presencial">Presencial</option>
                                                <option value="Virtual">Virtual</option>
                                        </select>
                                    </div>                                     
                                    
                                </div>

                                <div class="mb-2 col-2">
                                    <div class="btn-group">
                                        <button type="button" 
                                                class="btn btn-sm btn-alt-secondary" 
                                                data-bs-toggle="tooltip" 
                                                title="Agregar a cursos abiertos"
                                                data-id="{{ $idForm }}"
                                                onclick="asignarCursoAlCalendario(this)"
                                                >
                                            <i class="fa fa-fw fa-circle-right"></i>
                                        </button>
                                    </div>                             
                                </div>
                            </div>
                            

                        </form>
                    </td>
                </tr>
        @endforeach

    </tbody>
</table>


<script>
function asignarCursoAlCalendario(button) {
    const formId = button.getAttribute('data-id'); 
    Swal.fire({
        text: '¿Estás seguro de asignar este curso al calendario?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(`f-cc-${formId}`);
            if (form) {                
                form.submit();
            }
        }
    });
}
</script>