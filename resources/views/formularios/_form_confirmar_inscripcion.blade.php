<div class="row push">

    <div class="col-xl-7">

        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">
                Convenios
                </h3>
            </div>
            
            <div class="block-content block-content-full space-y-3">
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
                        $checked = ($convenio->getId() == $participante->getIdBeneficioConvenio()) ? 'checked' : '';

                        if ($participante->vinculadoUnicolMayor() && $participante->totalFormulariosInscritosPeriodoActual() > 0) {
                           $checked = '';
                        }
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

            </div>


            <div class="block-header">
                <h3 class="block-title">
                  Medios de pago
                </h3>                        
            </div>       
            <div class="block-content block-content-full pt-0">
              
              <div class="row g-3">
                  <!-- <div class="col-6 col-sm-4">
                    <div class="form-check form-block">
                      <input type="radio" class="form-check-input" id="medioPago-1" name="medioPago" value="pagoBanco" checked>
                      <label class="form-check-label bg-body-light text-center" for="medioPago-1">
                          Pago en Banco
                      </label>
                    </div>
                  </div> -->
                  <div class="col-6 col-sm-6">
                    <div class="form-check form-block">
                      <input type="radio" class="form-check-input" id="medioPago-2" name="medioPago" value="pagoDatafono" {{ old('medioPago') == 'pagoDatafono' ? 'checked' : '' }} checked>
                      <label class="form-check-label bg-body-light text-center" for="medioPago-2">
                          Pago Datáfono
                      </label>
                    </div>
                  </div>
                  
                  <div class="col-6 col-sm-6">
                    <div class="form-check form-block">
                      <input type="radio" class="form-check-input" id="medioPago-3" name="medioPago" value="pagoEcollect" {{ old('medioPago') == 'pagoEcollect' ? 'checked' : '' }}>
                      <label class="form-check-label bg-body-light text-center" for="medioPago-3">
                          ECollect
                      </label>
                    </div>
                  </div> 
                                            
                  <div class="mb-4" id="s-voucher" style="display: none;">
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
                                placeholder="Ingresar el valor de pago">                                   
                        <label class="form-label" for="valorPago">Valor a pagar</label>
                        @error('valorPago')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror                        
                    </div>
                  </div>

              </div>

            </div>             
            
            <!-- Información complementaria -->
            <div class="block-header">
                <h3 class="block-title">
                  Información complementaria
                </h3>                        
            </div>       
            <div class="block-content block-content-full pt-0">              
                <div class="form-floating">
                    <input type="text" 
                            class="js-flatpickr form-control" 
                            id="fec_max_legalizacion" 
                            name="fec_max_legalizacion" 
                            value="{{ old('fec_max_legalizacion') }}"
                            placeholder="Fec max. legalización">  
                    <label class="form-label" for="fec_max_legalizacion">Fec. max. legalización</label>
                </div>

                <div class="form-floating">
                    <input type="text" 
                            class="form-control" 
                            id="comentarios" 
                            name="comentarios" 
                            value="{{ old('comentarios', 'Sin comentarios') }}"
                            placeholder="Comentarios">  
                    <label class="form-label" for="comentarios">Comentarios</label>
                </div>                
            </div>

            <!-- Fin información complementaria -->

        </div>
        
    <!-- Fin listado de convenios -->
    </div>

    <!-- Resumen de los costos de matrícula -->
    <div class="col-xl-5 order-xl-last">

      <div class="block block-rounded">
          <div class="block-header">
            <h3 class="block-title">
              Resumen costo inscripción
            </h3>
          </div>
          <div class="block-content block-content-full">
            <span class="fw-100 text-muted">
              {{ $participante->getNombreCompleto() }} <br>
              {{ $participante->getDocumentoCompleto() }}
            </span>
            <table class="table table-vcenter">
              <tbody>
                <tr>
                  <td class="ps-0">
                    <a class="fw-semibold" href="javascript:void(0)">{{ $grupo->getNombreCurso() }}</a>
                    <div class="fs-sm text-muted">
                        G: {{ $grupo->getId() }} <br> 
                        {{ $grupo->getDia() }} / {{ $grupo->getJornada() }} <br>
                        {{ $grupo->getModalidad() }} <br>
                        Periodo: {{ $grupo->getNombreCalendario() }}
                    </div>
                  </td>
                  <td class="pe-0 fw-medium text-end" id="idCosto">{{ $grupo->getCostoFormateado() }}</td>
                </tr>
                <tr>
                  <td class="ps-0">
                    <a class="fw-semibold" href="javascript:void(0)">Descuento convenio</a>
                    <div class="fs-sm text-muted" id="idNombreDescuento">No aplica</div>
                  </td>
                  <td class="pe-0 fw-medium text-end" id="idValorDescuento">$0 COP</td>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td class="ps-0 fw-medium">Total</td>
                  <td class="pe-0 fw-bold text-end" id="idValorTotalAPagar">{{ $grupo->getCostoFormateado() }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <button class="btn btn-primary w-100 py-3 push">
          <i class="fa fa-check opacity-50 me-1"></i>
          {{ $btnText }}
        </button>
      </div>


    <!-- Fin resumen costos de matrícula -->
    </div>

</div>


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>

<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>

<script>
    $(document).ready(function(){

        // if($('input[name="medioPago"]:checked').val() == "pagoDatafono") {
            $("#s-voucher").show();
        // }

        if ($('input[name="convenio"]').is(':checked')) {
            checkearConvenio();
        }
        
        $('input[name="convenio"]').change(function(){
            checkearConvenio();
        });

        $('input[name="medioPago"]').change(function(){
          const medioPago = $('input[name="medioPago"]:checked').val();
            if (medioPago == "pagoEcollect") {
              var miRedirect = document.createElement('a');
              miRedirect.setAttribute('href', 'https://www.e-collect.com/customers/pagosunicolmayor.htm');
              miRedirect.setAttribute('target', '_blank');
              miRedirect.click();
            }
        });
    });

    function checkearConvenio() {
      const valor = $('input[name="convenio"]:checked').val();            
            var datosConvenio = valor.split('@');
            
            $("#convenioId").val(datosConvenio[0]); 

            var porcentajeDescuento = datosConvenio[1];
            var nombreDescuento = datosConvenio[2];            
            var valorCosto = $('#idCosto').text();

            valores = calcularTotalAPagar(valorCosto, porcentajeDescuento);

            $('#idNombreDescuento').text(nombreDescuento);
            
            $('#idValorDescuento').text(formatoMoneda( valores[1] ));
            $("#valor_descuento").val(valores[1]); 

            $('#idValorTotalAPagar').text(formatoMoneda( valores[0] ));
            $("#total_a_pagar").val(valores[0]); 
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