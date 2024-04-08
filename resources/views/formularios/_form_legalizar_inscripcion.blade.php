<div class="row push">

    <!-- Resumen de los costos de matrícula -->
    <div class="col-xl-6">

      <div class="block block-rounded">
          <div class="block-header">
            <h3 class="block-title">
              Resumen costo inscripción
            </h3>
          </div>
          <div class="block-content block-content-full">
            <span class="fw-100 text-muted">
              {{ $formulario->getParticipanteNombreCompleto() }} <br>
              {{ $formulario->getParticipanteTipoYDocumento() }}
            </span>
            <table class="table table-vcenter">
              <tbody>
                <tr>
                  <td class="ps-0">
                    <a class="fw-semibold" href="javascript:void(0)">{{ $formulario->getGrupoNombreCurso() }}</a>
                    <div class="fs-sm text-muted">
                        G: {{ $formulario->getGrupoId() }} <br> 
                        {{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }} <br>
                        {{ $formulario->getGrupoModalidad() }} <br>
                        Periodo: {{ $formulario->getGrupoCalendarioNombre() }}
                    </div>
                  </td>
                  <td class="pe-0 fw-medium text-end" id="idCosto">{{ $formulario->getGrupoCursoCosto() }}</td>
                </tr>
                <tr>
                  <td class="ps-0" colspan="2">
                    <a class="fw-semibold" href="javascript:void(0)">Descuento convenio</a>                 
                    <!-- <div class="fs-sm text-muted" id="idNombreDescuento">
                        @if ($formulario->tieneConvenio())                            
                            {{ $formulario->getConvenioNombre() }}
                        @else
                            No aplica
                        @endif
                    </div> -->
                  </td>                  
                  <!-- <td class="pe-0 fw-medium text-end" id="idValorDescuento">
                        @if ($formulario->tieneConvenio())                            
                            {{ $formulario->getValorDescuentoFormateado() }}
                        @else
                            $ 0 COP
                        @endif                                        
                  </td> -->
                </tr>
                <tr>
                  <td class="ps-0" colspan="2">
                  <div class="form-check form-block">
                    <input type="radio" class="form-check-input" id="convenio-0" value="0@0@No aplica" name="convenio" checked>
                    <label class="form-check-label" for="convenio-0">
                        <span class="d-block fw-normal p-1">
                            <span class="d-block fw-semibold mb-1">No aplica</span>
                        </span>
                    </label>
                </div>                    
                  @foreach ($convenios as $convenio)     
                    @if ($convenio->esVigente())    
                          @php
                            $checked = ($convenio->getId() == $formulario->getParticipanteIdBeneficioConvenio()) ? 'checked' : '';
                          @endphp 
                            <div class="form-check form-block">
                                <input type="radio" class="form-check-input" {{ $checked }} id="convenio-{{ $convenio->getId() }}" value="{{ $convenio->getId().'@'.$convenio->getDescuento().'@'.$convenio->getNombre() }}" name="convenio">
                                <label class="form-check-label" for="convenio-{{ $convenio->getId() }}">
                                    <span class="d-block fw-normal p-1">
                                    <span class="d-block fw-semibold mb-1">{{ $convenio->getNombre() }}</span>
                                    <span class="d-block fs-sm fw-medium text-muted"><span class="fw-semibold">{{ $convenio->getDescuento() }}%</span> de descuento</span>
                                    </span>
                                </label>
                            </div>  
                            @endif                   
                      @endforeach                       
                  </td>
                </tr>
                @if ($formulario->tienePagosParciales())
                  <tr>
                    <td class="ps-0" colspan="2">
                      <a class="fw-semibold" href="javascript:void(0)">Abonos realizados</a>
                    </td>
                  </tr>
                  @foreach ($formulario->PagosRealizados() as $pago)
                    <tr>
                      <td class="ps-0 fs-sm">
                        {{ $pago->getFechaFormateada() }}<br><small>{{ "Voucher: " . $pago->getVoucher() }}</small>
                    </td>
                    <td class="pe-0 fs-sm text-end">{{ $pago->getValorFormateado() }}</td>
                  </tr>                
                  @endforeach                                 
                @endif
                <tr>
                  <td class="ps-0 fs-sm fw-semibold">Valor a pagar</td>
                  <td class="pe-0 fs-sm text-end">
                    <a class="fw-semibold" href="javascript:void(0)" id="idPendientePorAPagar">
                      {{ $formulario->totalAPagarConDescuentoDePagoParcialFormateado() }}
                    </a>                    
                  </td>
                </tr>                 
              </tbody>
            </table>
          </div>
        </div>

    </div>


    <!-- Fin resumen costos de matrícula -->
    

    <div class="col-xl-6 order-xl-last">

        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">
                    Legalización
                </h3>
            </div>

            <div class="block-content block-content-full space-y-3">

                <div class="form-check form-block">

                    <div class="mb-4">
                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('voucher') is-invalid @enderror" 
                                   id="voucher" 
                                   name="voucher"
                                   value="{{ old('voucher') }}" 
                                   placeholder="Ingresar el número de voucher">                                   
                            <label class="form-label" for="voucher">Voucher</label>
                            @error('voucher')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror                            
                        </div>

                        <div class="form-floating">
                            <input type="text" 
                                   class="form-control @error('valorPago') is-invalid @enderror" 
                                   id="valorPago" 
                                   name="valorPago" 
                                   value="{{ old('valorPago') }}"
                                   placeholder="Ingresar el valor a pagar">                                   
                            <label class="form-label" for="valorPago">Valor a pagar</label>
                            @error('valorPago')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror                            
                        </div>                        
                    </div>

                    <div class="mb-4 text-center">
                      @if ($formulario->tieneComprobanteDePago())
                      <a href="{{ $formulario->getPathComprobantePago() }}" class="btn btn-lg rounded-pill btn-alt-info px-4 me-1 mb-3" target="_blank">
                          <i class="fa fa-download me-1"></i> Ver comprobante de pago
                      </a>                        
                      @endif
                    </div>

                  <div>
                    


                          <div class="block-content-full pt-0">

                            <div class="row g-3">

                              <div class="col-6 col-sm-6">
                                <div class="form-check form-block">
                                  <input type="radio" class="form-check-input" id="medioPago-2" name="medioPago" value="pagoDatafono" {{ old('medioPago') == 'pagoDatafono' ? 'checked' : '' }}>
                                  <label class="form-check-label bg-body-light text-center" for="medioPago-2">
                                      Pago Datáfono
                                  </label>
                                </div>
                              </div>

                              <div class="col-6 col-sm-6">
                                <div class="form-check form-block">
                                  <input type="radio" class="form-check-input" id="medioPago-3" name="medioPago" value="pagoEcollect" {{ old('medioPago') == 'pagoEcollect' || $formulario->pagadoPorEcollect() ? 'checked' : '' }}>
                                  <label class="form-check-label bg-body-light text-center" for="medioPago-3">
                                      ECollect
                                  </label>
                                </div>
                              </div>                               

                            </div>
                            
                          </div>
                                      
                    </div>                    

                </div>                        

            </div>  
            
        </div>
        
        <button class="btn btn-primary w-100 py-3 push">
            <i class="fa fa-check opacity-50 me-1"></i>
            {{ $btnText }}
        </button>        
    <!-- Fin listado de convenios -->
    </div>

</div>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script>
    $(document).ready(function(){

      if ($('input[name="convenio"]').is(':checked')) {
        checkearConvenio();
      }

      $('input[name="convenio"]').change(function(){   
            checkearConvenio();
      });

      $('input[name="medioPago"]').change(function(){
          const medioPago = $('input[name="medioPago"]:checked').val();
          $("#s-voucher").hide();
          if (medioPago=="pagoDatafono") {
            $("#s-voucher").show();
          } else {
            $("#voucher").val("");
          } 

        });      

    });

    function checkearConvenio() {      
      const valor = $('input[name="convenio"]:checked').val();            
            var datosConvenio = valor.split('@');
            
            $("#convenioId").val(datosConvenio[0]); 

            var porcentajeDescuento = datosConvenio[1];
            var nombreDescuento = datosConvenio[2];            
            var valorCosto = $('#costo_curso').val();

            valores = calcularTotalAPagar(valorCosto, porcentajeDescuento);
            
            $('#idValorDescuento').text(formatoMoneda( valores[1] ));
            $("#valor_descuento").val(valores[1]); 

            
            $('#idValorTotalAPagar').text(formatoMoneda( valores[0] ));
            $("#total_a_pagar").val(valores[0]); 
            $('#idCosto').text(formatoMoneda( valores[0] ));    

            var pendiente_por_pagar = valores[0] - $("#pago_parcial").val();
            $('#idPendientePorAPagar').text(formatoMoneda( pendiente_por_pagar ));  
            $("#valor_pendiente_por_pagar").val(pendiente_por_pagar);
            
    }

    function calcularTotalAPagar(valorCosto, porcentajeDescuento) {

        valorCosto = convertirFormatoCostoAEntero(valorCosto);
        var valorDescuento = calcularDescuento(valorCosto, porcentajeDescuento);
        var valorTotalAPagar = valorCosto - valorDescuento;

        var valores = new Array(valorTotalAPagar, valorDescuento);

        return valores;
    }

    function calcularDescuento(valorCosto, porcentajeDescuento) {
        if (porcentajeDescuento == 0) {
            return 0;
        }
        return valorCosto * ( porcentajeDescuento / 100 );
    }

    function convertirFormatoCostoAEntero(valorCosto) {
        var numeros = valorCosto.replace(/[^0-9]/g, '');
        return parseInt(numeros, 10);
    }

    function formatoMoneda(numero) {
        var numeroFormateado = '$' + numero.toLocaleString('es-CO', {
            minimumFractionDigits: 0
        }) + ' COP';
        
        return numeroFormateado;
    }    
</script>