@extends("plantillas.principal")

@php
    $titulo = "Devolución";    
@endphp

@section("title", $titulo)    

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form id="devolucion-form" action="{{ route('cambios_traslados.hacer_devolucion') }}" method="POST" class="mb-5">
        @csrf
        <input type="hidden" name="numero_formulario" id="numero_formulario" value="{{ $formulario->getNumero() }}">
        <input type="hidden" name="formulario_id" id="formulario_id" value="{{ $formulario->getId() }}">
        <input type="hidden" name="participante_id" id="participante_id" value="{{ $formulario->getParticipanteId() }}">
        <input type="hidden" name="calendario_id" id="calendario_id" value="{{ $periodo->getId() }}">        
        <input type="hidden" name="accion" id="accion" value="devolucion">
        <input type="hidden" name="porcentaje" id="porcentaje" value="">

        <!-- Empieza aqui lo propio de aplazamiento -->
        <div class="container-fluid py-4" style="background-color: #f8f9fa;">

            <div class="row">
                <!-- Datos Generales -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4" style="background-color: #ffffff; border-radius: 8px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3" style="background-color: #3a6eb8; padding: 10px; border-radius: 5px;">                                
                                <h5 class="card-title ml-2 fw-bold text-white">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                    Datos Generales
                                </h5>
                            </div>
                            <ul class="list-unstyled fs-xs mb-0" style="color: #2c3e50; line-height: 1.6;">
                                <li class="mb-3">
                                    <strong>Participante:</strong><br>
                                    {{ $formulario->getParticipanteNombreCompleto() }}<br>
                                    <span class="text-muted">{{ $formulario->getParticipanteTipoYDocumento() }}</span>
                                </li>
                                <li class="mb-3">
                                    <strong>Formulario:</strong><br>
                                    {{ $formulario->getNumero() }}
                                </li>
                                <li class="mb-3">
                                    <strong>Curso Actual:</strong><br>
                                    {{ $formulario->getGrupoNombreCurso() }}<br>
                                    <span class="text-muted">{{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }}</span>
                                </li>
                                <li class="mb-3">
                                    <strong>Costo del Curso:</strong><br>
                                    {{ $formulario->getGrupoCursoCosto() }}
                                </li>
                                <li class="mb-3">
                                    <strong>Convenio:</strong><br>
                                    {{ strlen($formulario->getConvenioNombre()) == 0 ? 'N/A' : $formulario->getConvenioNombre() }}
                                </li>
                                <li class="mb-3">
                                    <strong>Descuento:</strong><br>
                                    {{ $formulario->getValorDescuentoFormateado() }}
                                </li>
                                <li>
                                    <strong>{{ ($formulario->getEstado() == 'Pagado') ? 'Total Pagado' : 'Total a Pagar' }}:</strong><br>
                                    {{ $formulario->getTotalAPagarFormateado() }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Justificación y Aplazamiento-->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4" style="background-color: #ffffff; border-radius: 8px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3" style="background-color: #3a6eb8; padding: 10px; border-radius: 5px;">                                
                                <h5 class="card-title ml-2 fw-bold text-white">
                                <i class="fas fa-hand-holding-usd fa-lg"></i>
                                    Devoluciones
                                </h5>
                            </div>
                            
                            <!-- Justificación de la devolución -->
                            <div class="mb-3">
                                <label for="justificacion" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Justificación:</label>
                                <textarea class="form-control @error('justificacion') is-invalid @enderror fs-xs" id="justificacion" name="justificacion" style="height: 120px; background-color: #ffffff; border-color: #7f8c8d; border-radius: 5px;" placeholder="Escribe la justificación...">{{ old('justificacion') }}</textarea>
                                @error('justificacion')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>
                            <!-- Fin justificación -->

                            <!-- Origen de la solicitud de devolución -->
                            <div class="col-md-9 mb-3">
                                <label for="origen" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Solicitado por:</label>
                                <select class="form-select @error('origen') is-invalid @enderror fs-xs" id="origen" name="origen" style="width: 100%; background-color: #ecf0f1; border-color: #7f8c8d; border-radius: 5px;" data-placeholder="Selecciona un área...">
                                    <option></option>
                                    @foreach ($posibles_causas_devolucion as $causa)
                                    <option value="{{ $causa['value'] }}" {{ old('origen') == $causa['value'] ? 'selected' : '' }}>{{ $causa['nombre'] }}</option>
                                    @endforeach
                                </select>
                                @error('origen')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>                            

                            <!-- Valor a devolver -->
                            <div class="col-md-9 mb-3">
                                <label for="valor_devolucion" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Valor a devolver:</label>                             
                                <input type="text" 
                                    class="form-control @error('valor_devolucion') is-invalid @enderror fs-xs" 
                                    id="valor_devolucion" 
                                    name="valor_devolucion" 
                                    autocomplete="off"
                                    placeholder="Valor devolución"
                                    value="{{ old('valor_devolucion') }}"
                                    title="Valor a pagar">                                   
                                @error('valor_devolucion')
                                    <span class="invalid-feedback fs-xs" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>   
                            <!-- Fin valor a devolver -->


                            <div class="text-left mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="btn-confirmar">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Finaliza aqui lo propio de cambio de cursos -->
        
    </form>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>

<script>
    $(document).ready(function(){

        function formatCurrency(value) {
            return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
        }

        function removeCurrencyFormat(value) {
            // Remueve todo excepto los números
            return value.replace(/[^0-9]/g, '');
        }

        var totalAPagar = '{{ $formulario->getTotalAPagar() }}';
        $('#valor_devolucion').val(formatCurrency(totalAPagar));
        if ($(this).val() == "") {
            $('#valor_devolucion').val(formatCurrency(0));
        }

        $('#origen').change(function(){
            if ($(this).val() == "") {
                $("#porcentaje").val(0);
                $('#valor_devolucion').val(formatCurrency(0));
                return;
            }

            var origen = $(this).val();
            var valorDevolucion;

            if(origen === 'Participante'){
                $("#porcentaje").val(80);
                valorDevolucion = Math.round(totalAPagar * 0.8);
            } else if(origen === 'UnicolMayor'){
                $("#porcentaje").val(100);
                valorDevolucion = Math.round(totalAPagar);
            }

            $('#valor_devolucion').val(formatCurrency(valorDevolucion));
        });

        $('#btn-confirmar').click(function(e){
            var valorDevolucionConFormato = $('#valor_devolucion').val();
            var valorDevolucionSinFormato = removeCurrencyFormat(valorDevolucionConFormato);
            $('#valor_devolucion').val(valorDevolucionSinFormato);
        });
    });
</script>

@endsection
