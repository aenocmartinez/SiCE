<div class="row push">
    <!-- Tarjeta: Convenios Disponibles -->
    <div class="col-xl-4">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-xs">Convenios Disponibles</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row g-2">
                    <!-- Opción "No aplica" siempre de primero -->
                    <div class="col-md-6">
                        <div class="form-check form-block">
                            <input type="radio" class="form-check-input" id="convenio-0" value="0@0@No aplica" name="convenio" checked>
                            <label class="form-check-label fs-xs" for="convenio-0">
                                <span class="d-block fw-normal p-1">
                                    <span class="d-block fw-semibold mb-1">No aplica</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <!-- Listado de Convenios -->
                    @foreach ($convenios as $convenio)     
                        @if ($convenio->esVigente())    
                            @php
                                $checked = ($convenio->getId() == $formulario->getParticipanteIdBeneficioConvenio()) ? 'checked' : '';
                                $tagColor = 'bg-danger'; // Rojo por defecto
                                if ($convenio->getDescuento() >= 50) {
                                    $tagColor = 'bg-success'; // Verde para descuentos >= 50%
                                } elseif ($convenio->getDescuento() >= 20) {
                                    $tagColor = 'bg-warning'; // Amarillo para descuentos >= 20%
                                }
                            @endphp 
                            <div class="col-md-6">
                                <div class="form-check form-block border p-2">
                                    <input type="radio" class="form-check-input" {{ $checked }} id="convenio-{{ $convenio->getId() }}" value="{{ $convenio->getId().'@'.$convenio->getDescuento().'@'.$convenio->getNombre() }}" name="convenio">
                                    <label class="form-check-label fs-xs text-truncate" for="convenio-{{ $convenio->getId() }}">
                                        <span class="d-block fw-normal p-1 fs-xs text-truncate">
                                            <span class="badge {{ $tagColor }} text-white">{{ $convenio->getDescuento() }}%</span>
                                            <span class="d-block fw-semibold mb-1 mt-2">{{ $convenio->getNombre() }}</span>
                                        </span>
                                    </label>
                                </div>  
                            </div>
                        @endif                   
                    @endforeach 
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Tarjeta: Convenios Disponibles -->

    <!-- Tarjeta: Resumen del Curso -->
    <div class="col-xl-4">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-xs">Resumen del Curso</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- Datos del Participante -->
                <div class="mb-3">
                    <span class="fw-100 text-muted fs-xs">
                        {{ $formulario->getParticipanteNombreCompleto() }} <br>
                        {{ $formulario->getParticipanteTipoYDocumento() }}
                    </span>
                </div>
                <!-- Detalles del Curso -->
                <table class="table table-vcenter fs-xs mb-0">
                    <tbody>
                        <tr>
                            <td class="ps-0">
                                <a class="fw-semibold fs-xs" href="javascript:void(0)">
                                    <i class="fa fa-book me-2"></i>{{ $formulario->getGrupoNombreCurso() }}
                                </a>
                                <div class="fs-xs text-muted">
                                    G: {{ $formulario->getGrupoId() }} <br> 
                                    {{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }} <br>
                                    {{ $formulario->getGrupoModalidad() }} <br>
                                    Periodo: {{ $formulario->getGrupoCalendarioNombre() }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">
                                <i class="fa fa-dollar-sign me-2"></i><strong>Costo del curso:</strong> 
                                <span class="text-end fw-bold">{{ $formulario->getGrupoCursoCosto() }}</span>
                            </td>
                        </tr>   
                        <tr>
                            <td class="ps-0">
                                <i class="fa fa-tags me-2"></i><strong>Descuento por convenio:</strong> 
                                <span class="text-end fw-bold" id="idDescuentoNuevo"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0">
                                <i class="fa fa-calculator me-2"></i><strong>Costo real del curso:</strong> 
                                <span class="text-end fw-bold" id="idCosto"></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Fin Tarjeta: Resumen del Curso -->

    <!-- Tarjeta: Abonos Realizados y Legalización -->
    <div class="col-xl-4">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-xs">Abonos Realizados y Legalización</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- Abonos Realizados -->
                @if ($formulario->tienePagosParciales())
                    <h4 class="fs-xs">Abonos Realizados</h4>
                    @foreach ($formulario->PagosRealizados() as $pago)
                        @if ($pago->getValor() != 0)
                            <div class="d-flex justify-content-between fs-xs">
                                <span>{{ $pago->getFechaFormateada() }}<br><small>{{ "Voucher: " . $pago->getVoucher() }}</small></span>
                                <span>{{ $pago->getValorFormateado() }}</span>
                            </div>
                        @endif
                    @endforeach
                @endif
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <h3 class="fs-xs fw-semibold mb-0">Valor a pagar:</h3>
                    <h3 class="fs-xs fw-semibold mb-0 text-success" id="idPendientePorAPagar">
                        ${{ number_format((float) $formulario->totalAPagarConDescuentoDePagoParcialFormateado(), 0, ',', '.') }} COP
                    </h3>
                </div>

                <!-- Datos de Pago -->
                <h4 class="fs-xs mt-4">Legalización y Pago</h4>
                <div class="form-check form-block mb-4">
                    <div class="form-floating">
                        <input type="text" 
                               class="form-control fs-xs @error('voucher') is-invalid @enderror" 
                               id="voucher" 
                               name="voucher"
                               value="{{ old('voucher') }}" 
                               autocomplete="off"
                               placeholder="Ingresar el número de voucher">                                   
                        <label class="form-label fs-xs" for="voucher">Voucher</label>
                        @error('voucher')
                            <span class="invalid-feedback fs-xs" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                            
                    </div>

                    <div class="form-floating mt-3">
                        <input type="text" 
                               class="form-control fs-md text-success fw-bold text-center @error('valorPago') is-invalid @enderror" 
                               id="valorPago" 
                               name="valorPago" 
                               value="{{ old('valorPago') }}"
                               autocomplete="off"
                               placeholder="Ingresar el valor a pagar"
                               oninput="formatCurrency(this)">
                        <label class="form-label fs-xs" for="valorPago">Valor a pagar</label>
                        @error('valorPago')
                            <span class="invalid-feedback fs-xs" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                            
                    </div>    
                </div>

                <!-- Comentarios -->
                <div class="form-floating mb-4">
                    <textarea class="form-control fs-xs" 
                              id="comentarios" 
                              name="comentarios" 
                              placeholder="Comentarios adicionales"
                              title="Comentarios adicionales"
                              rows="5">{{ old('comentarios', $formulario->getComentarios()) }}</textarea>
                    <label class="form-label fs-xs" for="comentarios">Comentarios</label>
                </div>

                <!-- Comprobante de Pago -->
                <div class="mb-4 text-center">
                    @if ($formulario->tieneComprobanteDePago())
                        <a href="{{ url($formulario->getPathComprobantePago()) }}" class="btn btn-lg rounded-pill btn-alt-info px-4 me-1 mb-3" target="_blank">
                            <i class="fa fa-download me-1"></i> Ver comprobante de pago
                        </a>                        
                    @endif
                </div>

                <!-- Selección de Medio de Pago -->
                <div class="block-content-full pt-0">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-block">
                                <input type="radio" class="form-check-input" id="medioPago-2" name="medioPago" value="pagoDatafono" checked>
                                <label class="form-check-label bg-body-light text-center fs-xs" for="medioPago-2">
                                    <i class="fa fa-credit-card me-2"></i>Pago Datáfono
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-block">
                                <input type="radio" class="form-check-input" id="medioPago-3" name="medioPago" value="pagoEcollect" {{ old('medioPago') == 'pagoEcollect' || $formulario->pagadoPorEcollect() ? 'checked' : '' }}>
                                <label class="form-check-label bg-body-light text-center fs-xs" for="medioPago-3">
                                    <i class="fa fa-globe me-2"></i>ECollect
                                </label>
                            </div>
                        </div>                               
                    </div> 
                </div>                                  
            </div>  

            <!-- Botón de Confirmación -->
            <div class="block-content block-content-full">
                <button class="btn btn-primary w-100 py-3 push fs-xs">
                    <i class="fa fa-check opacity-50 me-1"></i>
                    {{ $btnText }}
                </button>
            </div>
        </div>
    </div>
    <!-- Fin Tarjeta: Abonos Realizados y Legalización -->
</div>

<script src="{{ asset('assets/js/lib/jquery.min.js') }}"></script>

<script>
    $(document).ready(function(){
        // Al cargar la página con una opción seleccionada
        checkearConvenio();

        // Evento: Cambio de convenio
        $('input[name="convenio"]').change(function() {
            checkearConvenio();
        });

        // Evento: Cambio de medio de pago
        $('input[name="medioPago"]').change(function() {
            const medioPago = $('input[name="medioPago"]:checked').val();
            $("#s-voucher").hide();

            if (medioPago === "pagoDatafono") {
                $("#s-voucher").show();
            } else {
                $("#voucher").val("");
                const miRedirect = document.createElement('a');
                miRedirect.setAttribute('href', 'https://www.e-collect.com/customers/plus/UColMayorProServicesPlus.htm');
                miRedirect.setAttribute('target', '_blank');
                miRedirect.click();
            }
        });

        // Evento: Formateo dinámico del valorPago
        $('#valorPago').on('input blur', function() {
            formatCurrency(this);
        });

        // Evento: Limpiar formato de moneda antes del submit
        $('form').submit(function() {
            $('#valorPago').val($('#valorPago').val().replace(/[^0-9]/g, ''));
        });
    });

    function checkearConvenio() {
        const valor = $('input[name="convenio"]:checked').val();
        const datosConvenio = valor.split('@');

        const convenioId = datosConvenio[0];
        const porcentajeDescuento = parseFloat(datosConvenio[1]);
        const nombreConvenio = datosConvenio[2];

        $("#convenioId").val(convenioId);

        const valorCurso = $('#costo_curso').val() || '{{ $formulario->getGrupoCursoCosto() }}';
        const valores = calcularTotalAPagar(valorCurso, porcentajeDescuento); // [valorTotal, valorDescuento]

        $('#idDescuentoNuevo').text(formatoMoneda(valores[1]));
        $("#valor_descuento").val(valores[1]); 

        $('#idCosto').text(formatoMoneda(valores[0]));
        $("#total_a_pagar").val(valores[0]); 

        // Calcular valor pendiente por pagar
        const abonos = parseInt($("#pago_parcial").val() || "0", 10);
        const pendiente = valores[0] - abonos;

        // Mostrar valor pendiente real en el input de valorPago
        const inputPago = document.getElementById("valorPago");
        inputPago.value = pendiente;
        formatCurrency(inputPago);

        // Mostrar también en el resumen
        $('#idPendientePorAPagar').text(`$${formatoMoneda(pendiente)} COP`);
        $("#valor_pendiente_por_pagar").val(pendiente);

        // Limpiar voucher si no es cooperativa
        const nombreConvenioLower = nombreConvenio.trim().toLowerCase();
        if (!nombreConvenioLower.includes("cooperativa")) {
            $("#voucher").val("");
        }
    }

    function calcularTotalAPagar(valorCosto, porcentajeDescuento) {
        const valorEntero = convertirFormatoCostoAEntero(valorCosto);
        const valorDescuento = calcularDescuento(valorEntero, porcentajeDescuento);
        const valorTotal = valorEntero - valorDescuento;
        return [valorTotal, valorDescuento];
    }

    function calcularDescuento(valorCosto, porcentajeDescuento) {
        if (!porcentajeDescuento || porcentajeDescuento === 0) return 0;
        return Math.floor(valorCosto * (porcentajeDescuento / 100));
    }

    function convertirFormatoCostoAEntero(valorCosto) {
        const limpio = valorCosto.toString().replace(/[^0-9]/g, '');
        return parseInt(limpio, 10) || 0;
    }

    function formatoMoneda(numero) {
        return numero.toLocaleString('es-CO', { minimumFractionDigits: 0 });
    }

    function formatCurrency(input) {
        let rawValue = input.value.replace(/[^0-9]/g, '');
        if (!rawValue) {
            input.value = '';
            return;
        }

        input.value = parseInt(rawValue, 10).toLocaleString('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        });

        // Lleva el cursor al final
        setTimeout(() => {
            input.selectionStart = input.selectionEnd = input.value.length;
        }, 0);
    }
</script>



