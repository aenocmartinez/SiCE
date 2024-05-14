<input type="hidden" name="medioPago" value="pagoEcollect">

   <div class="block block-rounded">
          <div class="block-header">
            <h3 class="block-title text-center">
              Resumen inscripción
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
                    <div class="fs-sm text-muted" id="idNombreDescuento">{{ $convenio->getNombre() }}</div>
                  </td>
                  <td class="pe-0 fw-medium text-end" id="idValorDescuento">
                    {{ $convenio->esCooperativa() ? '' : $descuentoFormateado }}
                  </td>
                </tr>                
              </tbody>
              <tbody>
                <tr>
                  <td class="ps-0 fw-medium">Total a pagar</td>
                  <td class="pe-0 fw-bold text-end" id="idValorTotalAPagar">
                    <a class="fw-semibold" href="javascript:void(0)"><h3>{{ $totalPagoFormateado }}</h3></a>
                </td>
                </tr>

                <tr>
                  <td class="ps-0 fw-sm text-center" colspan="10">
                    <button type="button" class="btn btn-success me-1 mb-1" onclick="confirmEcollect();">
                    <i class="fa fa-circle-dollar-to-slot me-1"></i>
                      Realizar pago en línea
                    </button>
                    <br>
                    <a href="{{asset('biblioteca/Instructivo_pago_en_linea_2.pdf')}}" target="_blank" style="font-size:14px;">
                        <i class="fa fa-download me-1"></i>
                        Consulta aquí el instructivo para pagos en línea por medio de la plataforma Ecollect
                    </a>
                  </td>
                </tr>
                <tr>
                  <td class="ps-0 fw-sm text-center" colspan="10" style="font-size:14px; padding-top:30px;">
                    <h2 class="text-danger">¡Importante!</h2>
                    <h5 class="text-warning">Una vez que hayas realizado el pago, es imprescindible que cargues el comprobante de pago en formato PDF en nuestro sistema.</h5>
                    Esto nos permitirá registrar tu transacción de manera adecuada e iniciar el proceso de verificación de tu inscripción, 
                    que tardará 3 días hábiles a partir de la fecha de carga del comprobante de pago.  Se te notificará al correo electrónico registrado en la inscripción.
                    <br><br>Recuerda que es responsabilidad del usuario asegurarse de que el comprobante de pago se cargue correctamente en el sistema.
                  </td>
                </tr>

              </tbody>
            </table>
          </div>

          <div class="col-8 container">
            <div class="input-group mb-5">
              <input class="form-control fs-sm" type="file" name="pdf" id="pdf" accept=".pdf">
              @error('pdf')
                  <div class="alert alert-danger">{{ $message }}</div>
              @enderror
              <button type="submit" class="btn btn-alt-primary fs-sm">
                  <i class="fa fa-fw fa-upload"></i> Cargar comprobante y finalizar
              </button>                        
            </div>
          </div>

          <br>

        </div>   
    
      </div>
    

