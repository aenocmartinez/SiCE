@php
    $totalPago = $grupo->getCosto();
    $descuento = 0;
    if ($convenio->existe()) {
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
                  <td class="ps-0 fw-medium">Total</td>
                  <td class="pe-0 fw-bold text-end" id="idValorTotalAPagar">{{ $totalPagoFormateado }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
    
      </div>
    

