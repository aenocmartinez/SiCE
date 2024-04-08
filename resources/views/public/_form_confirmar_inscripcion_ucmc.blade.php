<input type="hidden" name="estado" value="Pagado">

<div class="block block-rounded">
          <div class="block-header">
            <h3 class="block-title text-center">
              Resumen inscripción - UNICOLMAYOR
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
 
              </tbody>
            </table>

            <div class="text-center">
                <button class="btn btn-primary px-4 py-2" data-toggle="click-ripple">
                  <i class="fa fa-database me-1"></i>          
                  Confirmar inscripción
                </button>                
            </div>
          </div>

          <br>


        </div>   
    
      </div>
    

