<input type="hidden" name="estado" value="Revisar comprobante de pago">
<input type="hidden" name="medioPago" value="pagoEcollect">
<input type="hidden" name="flagComprobante" value="flagComprobante">

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
            <table class="table table-bordered table-vcenter fs-sm mt-3">
              @php
                  $total_a_pagar = 0;
                  $numero_cursos = 0;
                  if (session()->has('cursos_a_matricular')) {
                      $numero_cursos = count(Session::get('cursos_a_matricular'));
                  }        
              @endphp
              <thead>
                <tr class="text-center fs-sm">
                  <th>Curso</th>
                  <th>Costo</th>
                  <th>Descuento</th>
                  <th>Subtotal</th>
                </tr>
                <tbody>
                @foreach (Session::get('cursos_a_matricular') as $curso)   
                  <tr class="fs-sm">
                    <td>
                      <a class="text-info" href="#">
                        {{ 'G'.$curso['grupoId'] }} - {{ $curso['nombre_curso'] }}
                      </a>
                      <div class="fs-sm text-muted">{{ $curso['dia'] . " / " . $curso['jornada'] }}</div>                      
                    </td>
                    <td class="text-center">{{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($curso['costo_curso']) }}</td>
                    <td class="text-center">{{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($curso['descuento']) }}</td>
                    <td class="text-center">{{ $curso['totalPagoFormateado'] }}</td>
                  </tr>

                  @php
                    $total_a_pagar += $curso['totalPago']; 
                  @endphp

                @endforeach
                </tbody>
              </thead>

              <tbody>
                <tr>
                  <td class="ps-0 fw-bold h6">Total a pagar</td>
                  <td class="pe-3 fw-bold text-end" id="idValorTotalAPagar" colspan="3">
                    <a class="fw-semibold fs-sm" href="javascript:void(0)">
                      <h3>{{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($total_a_pagar) }}</h3>
                    </a>
                </td>
                </tr>

                <tr>
                  <td class="ps-0 fw-sm text-center" colspan="10" style="font-size:14px; padding-top:30px;">
                    <h2 class="text-danger">¡Importante!</h2>
                    <h5 class="text-muted">Una vez que hayas realizado el pago, es imprescindible que cargues el comprobante de pago en formato PDF en nuestro sistema.</h5>
                    Esto nos permitirá registrar tu transacción de manera adecuada e iniciar el proceso de verificación de tu inscripción, 
                    que tardará 3 días hábiles a partir de la fecha de carga del comprobante de pago.
                    <br><br>Recuerda que es responsabilidad del usuario asegurarse de que el comprobante de pago se cargue correctamente en el sistema.
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
              </tbody>
            </table>
          </div>

          <div class="col-8 container">
            <div class="input-group mb-5">
              <input class="form-control @error('pdf') is-invalid @enderror fs-sm" type="file" name="pdf" id="pdf" accept=".pdf">  
              <button type="submit" class="btn btn-alt-primary fs-sm">
                  <i class="fa fa-fw fa-upload"></i> Cargar comprobante y finalizar
              </button>                        
              @error('pdf')
                  <span class="invalid-feedback" role="alert">
                      {{ $message }}
                  </span>
              @enderror              
            </div>

          </div>
          <br>

        </div>   
    
      </div>
    

