<div class="row push">

    <!-- Resumen de los costos de matrícula -->
    <div class="col-xl-6">

      <div class="block block-rounded">
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
                    <a class="fw-semibold" href="javascript:void(0)">Descuento por convenio</a>
                    @if (!$formulario->tieneConvenio())
                    <div class="form-check form-block">                        
                          <label class="form-check-label" for="convenio-0">
                              <span class="d-block fw-normal p-1">
                              <span class="d-block fw-semibold mb-1">No aplica</span>
                              </span>
                          </label>
                      </div>      
                  @else
                    @foreach ($convenios as $convenio)     

                        @if ($convenio->getId() == $formulario->getParticipanteIdBeneficioConvenio())    
                          <div class="form-check form-block">                        
                              <label class="form-check-label" for="convenio-{{ $convenio->getId() }}">
                                  <span class="d-block fw-normal p-1">
                                    <span class="d-block fw-semibold mb-1">{{ $convenio->getNombre() }}</span>
                                    <span class="d-block fs-sm fw-medium text-muted"><span class="fw-semibold">{{ $convenio->getDescuento() }}%</span> de descuento</span>                                    
                                  </span>
                              </label>
                          </div>                      
                        @endif
                  
                    @endforeach   
                  @endif                    
                  </td>
                </tr>
                <tr>
                    <td class="ps-0">
                      <a class="fw-semibold" href="javascript:void(0)">¿Tiene pagos paricales?</a>
                    </td>
                    <td class="ps-0 text-end">
                      {{ $formulario->tienePagosParciales() ? 'SÍ' : 'NO' }}
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
                  <td class="ps-0 fs-medium fw-semibold">Valor a pagar</td>
                  <td class="pe-0 fs-sm text-end">
                    <a class="fw-medium" href="javascript:void(0)" id="idPendientePorAPagar">
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
                    Más información
                </h3>
            </div>

            <div class="block-content block-content-full space-y-3">

                <div class="form-check form-block">

                <div class="mb-3">
                    <a class="fw-semibold" href="javascript:void(0)">Estado</a>
                        <div class="form-floating fs-sm">
                              {{ $formulario->getEstado() }}
                        </div>                    
                    </div> 

                    <div class="mb-3">
                    <a class="fw-semibold" href="javascript:void(0)">Comentarios</a>
                        <div class="form-floating fs-sm">
                              {{ $formulario->getComentarios() }}                              
                        </div>                    
                    </div>

                    <div class="mb-3">
                    <a class="fw-semibold" href="javascript:void(0)">Fec. Max. Legalización</a>
                        <div class="form-floating fs-sm">
                              {{ Src\infraestructure\util\FormatoFecha::fechaFormateadaA5DeAgostoDe2024($formulario->getFechaMaxLegalizacion()) }}
                        </div>                    
                    </div>                    

                    @if ($formulario->tieneComprobanteDePago())
                    <div class="mb-1">
                    <a class="fw-semibold " href="javascript:void(0)">Comprobante de pago</a>
                        <div class="form-floating fs-sm text-center">
                        <a href="{{ $formulario->getPathComprobantePago() }}" class="btn btn-lg rounded-pill btn-alt-info px-4 me-1 mb-3 mt-2" target="_blank">
                          <i class="fa fa-download me-1"></i> Ver comprobante de pago
                      </a>   
                        </div>                    
                    </div>   
                    @endif                 


                  <div>
                    


                                      
                    </div>                    

                </div>                        

            </div>  
            
        </div>
    <!-- Fin listado de convenios -->
    </div>

</div>