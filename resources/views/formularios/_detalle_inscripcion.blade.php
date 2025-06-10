<div class="row push">
    <!-- Tarjeta: Resumen de los costos de matrícula -->
    <div class="col-xl-6 mb-4">
        <div class="block block-rounded shadow-sm">
            <div class="block-header bg-primary text-white">
                <h3 class="block-title fs-sm">Resumen de Matrícula</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- Datos del Participante -->
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        <i class="fa fa-user-circle fa-3x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold">{{ $formulario->getParticipanteNombreCompleto() }}</h4>
                        <span class="text-muted fs-sm">{{ $formulario->getParticipanteTipoYDocumento() }}</span>
                    </div>
                </div>

                <!-- Detalles del Curso -->
                <table class="table table-borderless fs-sm mb-0">
                    <tbody>
                        <tr>
                            <td>
                                <strong>{{ $formulario->getGrupoNombreCurso() }}</strong>
                                <div class="text-muted">
                                    <span>G: {{ $formulario->getGrupoId() }}</span><br>
                                    <span>{{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }}</span><br>
                                    <span>{{ $formulario->getGrupoModalidad() }}</span><br>
                                    <span>Periodo: {{ $formulario->getGrupoCalendarioNombre() }}</span>
                                </div>
                            </td>
                            <td class="text-end fw-bold text-primary fs-md" id="idCosto">{{ $formulario->getGrupoCursoCosto() }}</td>
                        </tr>
                        <!-- Descuento por Convenio -->
                        <tr>
                            <td colspan="2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">Descuento por Convenio</span>
                                    @if (!$formulario->tieneConvenio())
                                        <span class="text-muted">No aplica</span>
                                    @else
                                        @foreach ($convenios as $convenio)
                                            @if ($convenio->getId() == $formulario->getParticipanteIdBeneficioConvenio())
                                                <div>
                                                    <span class="badge bg-success">{{ $convenio->getDescuento() }}%</span>
                                                    <span class="text-muted">{{ $convenio->getNombre() }}</span>
                                                </div>
                                                <div class="text-end text-muted">
                                                    {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($formulario->getValorDescuento()) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <!-- Abonos Realizados -->
                        @if ($formulario->tienePagosParciales())
                            <tr>
                                <td colspan="2">
                                    <div class="fw-semibold mb-2">Abonos Realizados</div>
                                    <div class="list-group">
                                        @foreach ($formulario->PagosRealizados() as $pago)
                                            @if ($pago->getValor() != 0)
                                                <div class="list-group-item d-flex justify-content-between align-items-center p-2">
                                                    <div>
                                                        <span class="text-muted">{{ $pago->getFechaFormateada() }}</span><br>
                                                        <small class="text-muted">{{ $pago->getMedio() }} / voucher: {{ $pago->getVoucher() }}</small>
                                                    </div>
                                                    <div class="text-end fw-semibold">{{ $pago->getValorFormateado() }}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                        <!-- Valor a Pagar -->
                        <tr class="border-top">
                            <td class="fs-md fw-semibold">{{ $formulario->Pagado() ? 'Valor Pagado' : 'Valor a Pagar' }}</td>
                            <td class="text-end">
                                <h3 class="fw-bold text-success" id="idPendientePorAPagar">
                                    {{ $formulario->Pagado() ? Src\infraestructure\util\FormatoMoneda::PesosColombianos($formulario->TotalPagoRealizado()) : $formulario->totalAPagarConDescuentoDePagoParcialFormateado() }}
                                </h3>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Fin Tarjeta: Resumen de los costos de matrícula -->
    
    <!-- Tarjeta: Más Información -->
    <div class="col-xl-6 order-xl-last mb-4">
        <div class="block block-rounded shadow-sm">
            <div class="block-header bg-primary text-white">
                <h3 class="block-title fs-sm">Más Información</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- Información Adicional -->
                <div class="mb-3">
                    <h4 class="fw-semibold">Detalles Adicionales</h4>
                    <!-- Estado -->
                    <div class="mb-3">
                        <span class="fw-semibold">Estado:</span>
                        <div class="fs-sm text-muted">{{ $formulario->getEstado() }}</div>
                    </div>
                    <!-- Medio de Inscripción -->
                    <div class="mb-3">
                        <span class="fw-semibold">Medio de inscripción:</span>
                        <div class="fs-sm text-muted">{{ $formulario->getMedioInscripcion() }}</div>
                    </div>
                    <!-- Comentarios -->
                    <div class="mb-3">
                        <span class="fw-semibold">Comentarios:</span>
                        <div class="fs-sm text-muted">{{ $formulario->getComentarios() }}</div>
                    </div>
                    <!-- Fecha de Creación -->
                    <div class="mb-3">
                        <span class="fw-semibold">Fecha de Creación:</span>
                        <div class="fs-sm text-muted">{{ \Carbon\Carbon::parse($formulario->getFechaCreacion())->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</div>
                    </div>
                    <!-- Fecha Máxima de Legalización -->
                    <div class="mb-3">
                        <span class="fw-semibold">Fecha Máxima de Legalización:</span>
                        <div class="fs-sm text-muted">{{ \Carbon\Carbon::parse($formulario->getFechaMaxLegalizacion())->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</div>
                    </div>

                    <!-- Comprobante de Pago -->
                    @if ($formulario->tieneComprobanteDePago())
                        <div class="text-center mt-4">

                            @if ($formulario->tieneComprobanteDePago())
                                <a href="{{ url($formulario->getPathComprobantePago()) }}" class="btn btn-lg rounded-pill btn-alt-info px-4" target="_blank">
                                    <i class="fa fa-download me-1"></i> Ver comprobante de pago
                                </a>
                            @else
                                <p>No se encontró el comprobante de pago.</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <!-- Fin Tarjeta: Más Información -->
</div>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
