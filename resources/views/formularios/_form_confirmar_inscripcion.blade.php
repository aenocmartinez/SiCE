<div class="row push">
    <!-- Sección de Convenios -->
    <div class="col-xl-4">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-xs">Convenios</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row g-3">
                    <!-- Opción "No aplica" siempre primera -->
                    <div class="col-6">
                        <div class="form-check form-block">
                            <input type="radio" class="form-check-input" id="convenio-0" value="0@0@No aplica" name="convenio" checked>
                            <label class="form-check-label" for="convenio-0">
                                <span class="d-block fw-normal p-1 fs-xs text-truncate">
                                    <span class="badge bg-secondary text-white">0%</span>
                                    <span class="d-block fw-semibold mb-1 mt-2">No aplica</span>
                                </span>
                            </label>
                        </div>
                    </div>
                    @foreach ($convenios as $convenio)
                        @if ($convenio->esVigente())
                            @php
                                $descuento = $convenio->getDescuento();
                                $tagColor = 'bg-danger'; // Rojo por defecto
                                if ($descuento >= 50) {
                                    $tagColor = 'bg-success'; // Verde para descuentos >= 50%
                                } elseif ($descuento >= 20) {
                                    $tagColor = 'bg-warning'; // Amarillo para descuentos >= 20%
                                }
                            @endphp
                            <div class="col-6">
                                <div class="form-check form-block">
                                    <input type="radio" class="form-check-input" id="convenio-{{ $convenio->getId() }}" value="{{ $convenio->getId().'@'.$descuento.'@'.$convenio->getNombre() }}" name="convenio">
                                    <label class="form-check-label" for="convenio-{{ $convenio->getId() }}">
                                        <span class="d-block fw-normal p-1 fs-xs text-truncate">
                                            <span class="badge {{ $tagColor }} text-white">{{ $descuento }}%</span>
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

    <!-- Resumen de los costos de matrícula -->
    <div class="col-xl-4">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-xs">Resumen costo inscripción</h3>
            </div>
            <div class="block-content block-content-full">
                <span class="fw-100 text-muted fs-xs">
                    {{ $participante->getNombreCompleto() }} <br>
                    {{ $participante->getDocumentoCompleto() }}
                </span>
                <table class="table table-vcenter fs-xs">
                    <tbody>
                        <tr>
                            <td class="ps-0">
                                <a class="fw-semibold fs-xs" href="javascript:void(0)">
                                    <i class="fa fa-book me-2"></i>{{ $grupo->getNombreCurso() }}
                                </a>
                                <div class="fs-xs text-muted">
                                    G: {{ $grupo->getId() }} <br> 
                                    {{ $grupo->getDia() }} / {{ $grupo->getJornada() }} <br>
                                    {{ $grupo->getModalidad() }} <br>
                                    Periodo: {{ $grupo->getNombreCalendario() }}
                                </div>
                            </td>
                            <td class="pe-0 fw-medium text-end fs-xs" id="idCosto">{{ $grupo->getCostoFormateado() }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0">
                                <a class="fw-semibold fs-xs" href="javascript:void(0)">
                                    <i class="fa fa-tag me-2"></i>Descuento convenio
                                </a>
                                <div class="fs-xs text-muted" id="idNombreDescuento">No aplica</div>
                            </td>
                            <td class="pe-0 fw-medium text-end fs-xs" id="idValorDescuento">$0 COP</td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <td class="ps-0 fw-medium fs-xs">Total</td>
                            <td class="pe-0 fw-bold text-end fs-xs text-success" id="idValorTotalAPagar">{{ $grupo->getCostoFormateado() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nueva tarjeta de Saldos por aplazamientos -->
        <div class="block block-rounded mt-4">
            <div class="block-header">
                <h3 class="block-title fs-xs">Saldos por aplazamientos</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row g-3">
                    @forelse ($participante->getAplazamientos() as $ap)
                        <div class="col-12 border-bottom">                            
                            <!-- <input type="checkbox" class="form-check-input me-2" id="saldo-{{ $loop->index }}" name="saldo[]" value="{{ $ap->getId() }}"> -->
                            <label class="form-check-label" for="saldo-{{ $loop->index }}">
                                <span class="fw-semibold fs-xs">                                    
                                {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($ap->getSaldo()) }}
                                </span> 
                                -                                                                 
                                <span class="fs-xs">Caduca el {{ Src\infraestructure\util\FormatoFecha::fechaFormateadaA5DeAgostoDe2024($ap->getFechaCaducidad()) }}</span>
                                <br>
                                <input type="checkbox" class="form-check-input me-2" id="saldo-{{ $loop->index }}" name="ids_de_aplazamientos_para_redimir[]" value="{{ $ap->getId() }}"> 
                                <label class="form-check-label fs-xs">Redimir</label>                                
                            </label>
                            <div class="mt-2">
                                <a href="javascript:void(0)" class="fs-xs text-primary toggle-comment" data-target="#comentario-{{ $loop->index }}">Mostrar comentario</a>
                                <div id="comentario-{{ $loop->index }}" class="comentario fs-xs text-muted d-none mt-1">
                                    {{ $ap->getComentarios() }}
                                    <br/><br/>
                                    <label for="vouchers">No. Voucher:</label>
                                    <ul>
                                        @foreach ($ap->getVouchers() as $item)
                                            @if ($item['voucher'] != 0)                                            
                                                <li>{{ $item['voucher'] }}</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                        
                                </div>
                            </div>
                            
                        </div>                        
                    @empty
                        <div class="col-12">                            
                            <label class="form-check-label" for="saldo-no">
                                <span class="fs-xs">No cuenta con saldos por aplazamientos</span>
                            </label>
                        </div>                        
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Medios de Pago, Abono e Información Complementaria -->
    <div class="col-xl-4">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title fs-xs">Medios de pago</h3>                        
            </div>       
            <div class="block-content block-content-full pt-0">
                <div class="row g-3">
                    <div class="col-12">
                        <input type="radio" class="form-check-input" id="medioPago-2" name="medioPago" value="pagoDatafono" {{ old('medioPago') == 'pagoDatafono' ? 'checked' : '' }} checked>
                        <label class="form-check-label bg-body-light text-center fs-xs" for="medioPago-2" title="Pagar con datáfono">
                            <i class="fa fa-credit-card me-2"></i>Pago Datáfono
                        </label>
                    </div>
                    <div class="col-12">
                        <input type="radio" class="form-check-input" id="medioPago-3" name="medioPago" value="pagoEcollect" {{ old('medioPago') == 'pagoEcollect' ? 'checked' : '' }}>
                        <label class="form-check-label bg-body-light text-center fs-xs" for="medioPago-3" title="Pagar con ECollect">
                            <i class="fa fa-globe me-2"></i>ECollect
                        </label>
                    </div>
                    <div class="mb-4" id="s-voucher">
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('voucher') is-invalid @enderror fs-xs" 
                                   id="voucher" 
                                   name="voucher" 
                                   value="{{ old('voucher') }}"
                                   autocomplete="off"
                                   placeholder="Ingresar el número de voucher"
                                   title="Número de voucher">                                   
                            <label class="form-label fs-xs" for="voucher">Voucher</label>
                            @error('voucher')
                                <span class="invalid-feedback fs-xs" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('abono') is-invalid @enderror fs-xs" 
                                   id="abono" 
                                   name="abono" 
                                   autocomplete="off"
                                   placeholder="Ingresar el abono"
                                   title="Abono">                                   
                            <label class="form-label fs-xs" for="abono">Abono</label>
                            @error('abono')
                                <span class="invalid-feedback fs-xs" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror                        
                        </div>
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('valorPago') is-invalid @enderror fs-xs" 
                                   id="valorPago" 
                                   name="valorPago" 
                                   autocomplete="off"
                                   placeholder="Valor a pagar"
                                   title="Valor a pagar">                                   
                            <label class="form-label fs-xs" for="valorPago">Valor a pagar</label>
                            @error('valorPago')
                                <span class="invalid-feedback fs-xs" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror                        
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block block-rounded">
            <!-- Información complementaria -->
            <div class="block-header">
                <h3 class="block-title fs-xs">Información complementaria</h3>                        
            </div>       
            <div class="block-content block-content-full pt-0">              
                <div class="form-floating">
                    <input type="text" 
                           class="js-flatpickr form-control fs-xs" 
                           id="fec_max_legalizacion" 
                           name="fec_max_legalizacion" 
                           value="{{ old('fec_max_legalizacion') }}"
                           placeholder="Fecha máxima de legalización"
                           title="Fecha máxima de legalización">  
                    <label class="form-label fs-xs" for="fec_max_legalizacion">Fec. max. legalización</label>
                </div>

                <div class="form-floating">
                    <textarea class="form-control fs-xs" 
                              id="comentarios" 
                              name="comentarios" 
                              placeholder="Comentarios"
                              title="Comentarios adicionales"
                              rows="10">{{ old('comentarios') }}</textarea>
                    <label class="form-label fs-xs" for="comentarios">Comentarios</label>
                </div>                
            </div>
        </div>

        <button class="btn btn-primary w-100 py-3 mt-4 fs-xs" style="background-color: #007bff;">
            <i class="fa fa-check opacity-50 me-1"></i>
            {{ $btnText }}
        </button>
    </div>
</div>

<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>

<script>
$(document).ready(function() {
    const valorPagoInput = document.getElementById('valorPago');
    const abonoInput = document.getElementById('abono');

    // Mostrar y ocultar comentarios
    $('.toggle-comment').click(function() {
        const target = $(this).data('target');
        $(target).toggleClass('d-none');
        const text = $(this).text() === 'Mostrar comentario' ? 'Ocultar comentario' : 'Mostrar comentario';
        $(this).text(text);
    });

    abonoInput.addEventListener('input', function(event) {
        let value = event.target.value;
        value = value.replace(/\D/g, ''); // Elimina caracteres no numéricos
        value = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
        event.target.value = value;
        valorPagoInput.value = '';
    });

    valorPagoInput.addEventListener('input', function(event) {
        let value = event.target.value;
        value = value.replace(/\D/g, ''); // Elimina caracteres no numéricos
        value = new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
        event.target.value = value;
    });

    valorPagoInput.form.addEventListener('submit', function() {
        abonoInput.value = abonoInput.value.replace(/[^0-9]/g, '');
        valorPagoInput.value = valorPagoInput.value.replace(/[^0-9]/g, '');
        if (abonoInput.value.trim() !== '') {
        valorPagoInput.value = abonoInput.value;
    }        
    });

    $('input[name="convenio"]').change(function(){
        checkearConvenio();
    });

    function checkearConvenio() {
        const valor = $('input[name="convenio"]:checked').val();            
        const datosConvenio = valor.split('@');
        
        $("#convenioId").val(datosConvenio[0]); 

        const porcentajeDescuento = datosConvenio[1];
        const nombreDescuento = datosConvenio[2];            
        const valorCosto = $('#idCosto').text();

        const valores = calcularTotalAPagar(valorCosto, porcentajeDescuento);

        $('#idNombreDescuento').text(nombreDescuento);
        $('#idValorDescuento').text(formatoMoneda(valores[1]));
        $("#valor_descuento").val(valores[1]); 

        $('#idValorTotalAPagar').text(formatoMoneda(valores[0]));
    }

    function calcularTotalAPagar(valorCosto, porcentajeDescuento) {
        valorCosto = convertirFormatoCostoAEntero(valorCosto);
        const valorDescuento = calcularDescuento(valorCosto, porcentajeDescuento);
        const valorTotalAPagar = valorCosto - valorDescuento;

        return [valorTotalAPagar, valorDescuento];
    }

    function calcularDescuento(valorCosto, porcentajeDescuento) {
        return porcentajeDescuento == 0 ? 0 : valorCosto * (porcentajeDescuento / 100);
    }

    function convertirFormatoCostoAEntero(valorCosto) {
        return parseInt(valorCosto.replace(/[^0-9]/g, ''), 10);
    }

    function formatoMoneda(numero) {
        return '$' + numero.toLocaleString('es-CO', {minimumFractionDigits: 0}) + ' COP';
    }
});
</script>
