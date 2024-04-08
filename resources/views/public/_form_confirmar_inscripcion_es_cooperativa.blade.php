<input type="hidden" name="estado" value="Pendiente de pago">
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
                  <td class="ps-0" colspan="10">
                    <a class="fw-semibold" href="javascript:void(0)">{{ $grupo->getNombreCurso() }}</a>
                    <div class="fs-sm text-muted">
                        G: {{ $grupo->getId() }} <br> 
                        {{ $grupo->getDia() }} / {{ $grupo->getJornada() }} <br>
                        {{ $grupo->getModalidad() }} <br>
                        Periodo: {{ $grupo->getNombreCalendario() }}
                    </div>
                  </td>
                </tr>
               
                <tr>
                  <td class="ps-0" colspan="10">
                    <a class="fw-semibold" href="javascript:void(0)">Convenio</a>
                    <div class="fs-sm text-muted" id="idNombreDescuento">{{ $convenio->getNombre() }}</div>
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
    

