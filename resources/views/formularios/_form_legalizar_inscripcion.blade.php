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
                  <td class="ps-0">
                    <a class="fw-semibold" href="javascript:void(0)">Descuento convenio</a>
                    <div class="fs-sm text-muted" id="idNombreDescuento">
                        @if ($formulario->tieneConvenio())                            
                            {{ $formulario->getConvenioNombre() }}
                        @else
                            No aplica
                        @endif
                    </div>
                  </td>
                  <td class="pe-0 fw-medium text-end" id="idValorDescuento">
                        @if ($formulario->tieneConvenio())                            
                            {{ $formulario->getValorDescuentoFormateado() }}
                        @else
                            $ 0 COP
                        @endif                                        
                  </td>
                </tr>
              </tbody>
              <tbody>
                <tr>
                  <td class="ps-0 fw-medium">Total</td>
                  <td class="pe-0 fw-bold text-end" id="idValorTotalAPagar">{{ $formulario->getTotalAPagarFormateado() }}</td>
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
                                   placeholder="Ingresar el número de voucher">                                   
                            <label class="form-label" for="voucher">Voucher</label>
                            @error('voucher')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror                            
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="comprobante">Comprobante de pago</label>
                        <input class="form-control" type="file" id="comprobante"> 
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

<!-- 
<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>
<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script> -->
