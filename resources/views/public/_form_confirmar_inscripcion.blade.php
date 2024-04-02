@php
    $totalPago = $grupo->getCosto();
    $descuento = 0;
    if ($convenio->existe()) {
        $descuento = $grupo->getCosto() * ($convenio->getDescuento()/100);
        $totalPago = $totalPago - $descuento;
    }

    if ($participante->vinculadoUnicolMayor()) {
      $convenio = new Src\domain\Convenio(env('CONVENIO_NOMBRE_UNICOLMAYOR'));
      $convenio->setId(env('CONVENIO_ID_UNICOLMAYOR'));
      $convenio->setDescuento(100);

      $descuento = $grupo->getCosto() * ($convenio->getDescuento()/100);
      $totalPago = $totalPago - $descuento;      
    }

    $descuentoFormateado = Src\infraestructure\util\FormatoMoneda::PesosColombianos($descuento);
    $totalPagoFormateado = Src\infraestructure\util\FormatoMoneda::PesosColombianos($totalPago);
@endphp


<input type="hidden" name="participanteId" value="{{ $participante->getId() }}">
<input type="hidden" name="total_a_pagar" value="{{ $totalPago }}">
<input type="hidden" name="valor_descuento" value="{{ $descuento }}">
<input type="hidden" name="convenioId" value="{{ $convenio->getId() }}">
<input type="hidden" name="grupoId" value="{{ $grupo->getId() }}">
<input type="hidden" name="costo_curso" value="{{ $grupo->getCosto() }}">
    
    
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
                @if ($convenio->existe())                    
                <tr>
                  <td class="ps-0">
                    <a class="fw-semibold" href="javascript:void(0)">Descuento convenio</a>
                    <div class="fs-sm text-muted" id="idNombreDescuento">{{ $convenio->getNombre() }}</div>
                  </td>
                  <td class="pe-0 fw-medium text-end" id="idValorDescuento">{{ $descuentoFormateado }}</td>
                </tr>                
                @endif
              </tbody>
              <tbody>
                <tr>
                  <td class="ps-0 fw-medium">Total a pagar</td>
                  <td class="pe-0 fw-bold text-end" id="idValorTotalAPagar">
                    <a class="fw-semibold" href="javascript:void(0)"><h3>{{ $totalPagoFormateado }}</h3></a>
                </td>
                </tr>

                @if ( $participante->vinculadoUnicolMayor() )
                <tr>
                  <td class="ps-0 fw-sm text-center" colspan="10">                                
                  <button class="btn btn-primary px-4 py-2" data-toggle="click-ripple">
                    <i class="fa fa-database me-1"></i>          
                    Confirmar inscripción
                  </button>                
                  </td>
                </tr>
                @else 
                <tr>
                  <td class="ps-0 fw-sm text-center" colspan="10">
                    <button type="button" class="btn btn-success me-1 mb-1" onclick="confirmEcollect();">
                    <i class="fa fa-circle-dollar-to-slot me-1"></i>
                        Realizar pago por Ecollect
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
                @endif
              </tbody>
            </table>
          </div>

          @if (!$participante->vinculadoUnicolMayor() )
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
          @endif
          <br>


        </div>   
    
      </div>
    

