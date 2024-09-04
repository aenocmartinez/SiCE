@extends("plantillas.principal")

@php
    $titulo = "Nuevo trámite";    
@endphp

@section("title")
    Cambio de Curso o Grupo
@endsection

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form action="{{ route('cambios_traslados.realizar_cambio_de_grupo') }}" method="POST" class="mb-5">
        @csrf
        <input type="hidden" name="numero_formulario" id="numero_formulario" value="{{ $formulario->getNumero() }}">
        <input type="hidden" name="grupoId" id="grupoId" value="">
        <input type="hidden" name="accion" id="accion" value="cambio">
        <input type="hidden" name="estado" id="estado" value="{{ $formulario->getEstado() }}">
        <input type="hidden" name="costo_curso_actual" id="costo_curso_actual" value="{{ $formulario->getTotalAPagar() }}">
        <input type="hidden" name="tiene_convenio" id="tiene_convenio" value="{{ $formulario->tieneConvenio() }}">
        <input type="hidden" name="porcentaje_descuento" id="porcentaje_descuento" value="{{ $formulario->getConvenioDescuento() }}">
        <input type="hidden" name="decision_sobre_pago" id="decision_sobre_pago" value="devolución">
        <input type="hidden" name="calendario_id" id="calendario_id" value="{{ $periodo->getId() }}">

        <!-- Empieza aqui lo propio de cambio de cursos -->
        <div class="container-fluid py-4" style="background-color: #f8f9fa;">

            <!-- Sección: Datos Generales y Cambio de Curso -->
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

                <!-- Cambio de Curso, Resumen de Pagos, y Justificación -->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4" style="background-color: #ffffff; border-radius: 8px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3" style="background-color: #3a6eb8; padding: 10px; border-radius: 5px;">
                                <i class="fas fa-exchange-alt fa-lg" style="color: #ffffff;"></i>
                                <h5 class="card-title ml-2" style="color: #ffffff; font-weight: bold;">Cambio de Curso</h5>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="area_id" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Selecciona un Área:</label>
                                    <select class="js-select2 form-select @error('area_id') is-invalid @enderror fs-xs" id="area_id" name="area_id" style="width: 100%; background-color: #ecf0f1; border-color: #7f8c8d; border-radius: 5px;" data-placeholder="Selecciona un área...">
                                        <option></option>
                                        @foreach ($areas as $area)
                                        <option value="{{ $area->getId() }}" {{ old('area_id') == $area->getId() ? 'selected' : '' }}>{{ $area->getNombre() }}</option>
                                        @endforeach
                                    </select>
                                    @error('area_id')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="nuevo_curso" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Selecciona el Nuevo Curso:</label>
                                    <select class="js-select2 form-select @error('nuevo_curso') is-invalid @enderror fs-xs" id="nuevo_curso" name="nuevo_curso" style="width: 100%; background-color: #ecf0f1; border-color: #7f8c8d; border-radius: 5px;" data-placeholder="Selecciona un curso...">
                                        <option value="">Seleccione un curso...</option>
                                    </select>
                                    @error('nuevo_curso')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Justificación del Cambio -->
                            <div class="mb-4">
                                <label for="justificacion" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Justificación del Cambio:</label>
                                <textarea class="form-control @error('justificacion') is-invalid @enderror fs-xs" id="justificacion" name="justificacion" style="height: 120px; background-color: #ffffff; border-color: #7f8c8d; border-radius: 5px;" placeholder="Escribe la justificación...">{{ old('justificacion') }}</textarea>
                                @error('justificacion')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <!-- Resumen de Pagos -->
                            <div id="saldo-a-favor">
                                <div class="alert alert-success d-flex align-items-center" style="background-color: #2e7d32; color: #ffffff; border-radius: 5px; display: none;">
                                    <i class="fas fa-check-circle fa-lg mr-2"></i>
                                    <div>
                                        <p class="fs-xs mb-1"><strong>Saldo a favor:</strong></p>
                                        <h4 id="valor-saldo-a-favor" class="mb-0"></h4>
                                        <p class="fs-xs mb-0">El curso seleccionado tiene un costo menor. Se realizará una devolución automática.</p>
                                    </div>
                                </div>
                            </div>

                            <div id="saldo-en-contra">
                                <div class="alert alert-danger d-flex align-items-center" style="background-color: #c62828; color: #ffffff; border-radius: 5px; display: none;">
                                    <i class="fas fa-exclamation-circle fa-lg mr-2"></i>
                                    <div>
                                        <p class="fs-xs mb-1"><strong>Saldo en contra:</strong></p>
                                        <h4 id="valor-saldo-en-contra" class="mb-0"></h4>
                                        <p class="fs-xs mb-0">El curso seleccionado tiene un costo mayor. Debe realizar el pago restante para legalizar la matrícula.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Confirmar Cambio</button>
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

<link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.min.css')}}">
<script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script>

<script>One.helpersOnLoad(['jq-select2']);</script>

<script>
    $(document).ready(function() {
        // Ocultar secciones al cargar la página
        $("#saldo-a-favor").hide();
        $("#saldo-en-contra").hide();

        $("#area_id").change(function() {
            $("#saldo-a-favor").hide();
            $("#saldo-en-contra").hide();

            // Mostrar el indicador de cargando en el select de nuevo curso con un ícono
            $("#nuevo_curso").html('<option disabled selected class="text-center">Cargando cursos...</option>');

            const area_id = $('#area_id').val();
            var url = "{{ route('cambios-traslados.cursos-para-matricular', ['area_id' => ':paramId']) }}";
            url = url.replace(':paramId', area_id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(resp) {
                    $("#nuevo_curso").html(resp);
                },
                error: function() {
                    // En caso de error, mostrar un mensaje
                    $("#nuevo_curso").html('<option disabled style="background-color: #d9534f; color: #fff; font-weight: bold;">Error al cargar cursos</option>');
                }
            });
        });

        $("#nuevo_curso").change(function() {
            $("#saldo-a-favor").hide();
            $("#saldo-en-contra").hide();

            const value = $('#nuevo_curso').val();
            data = value.split('@');
            if (data.length == 0) {
                return;
            }
            const grupo_id = data[0];
            const costo_nuevo_curso = data[1];
            const valor_pagado_curso_inicial = $('#costo_curso_actual').val();
            const tiene_convenio = $('#tiene_convenio').val();
            const porcentaje_descuento = $("#porcentaje_descuento").val();
            var valor_descuento = 0;
            $("#grupoId").val(grupo_id);

            if (tiene_convenio) {
                valor_descuento = costo_nuevo_curso * (porcentaje_descuento / 100);
            }

            var nuevo_valor_a_pagar = costo_nuevo_curso - valor_descuento;
            var aux_valor_saldo_a_favor = valor_pagado_curso_inicial - nuevo_valor_a_pagar;

            if (aux_valor_saldo_a_favor == 0) {
                return;
            }

            if (aux_valor_saldo_a_favor < 0) {
                $("#saldo-en-contra").fadeIn();
                $("#valor-saldo-en-contra").text(formatoDeMoneda(aux_valor_saldo_a_favor * -1));
            }

            if (aux_valor_saldo_a_favor > 0) {
                if ($("#estado").val() == 'Pendiente de pago') {
                    return;
                }
                $("#saldo-a-favor").fadeIn();
                $("#valor-saldo-a-favor").text(formatoDeMoneda(aux_valor_saldo_a_favor));
            }
        });
    });

    function formatoDeMoneda(numero) {
        var opciones = {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        };
        var numeroFormateado = numero.toLocaleString('es-CO', opciones);
        return numeroFormateado.replace('COP', '').trim() + ' COP';
    }
</script>

@endsection
