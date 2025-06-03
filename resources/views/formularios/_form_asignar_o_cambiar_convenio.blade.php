<div class="row">
    <div class="col-12">
        <div class="block block-rounded">

            <div class="block-content block-content-full">
                
                <!-- Datos del participante -->
                <div class="mb-4">
                    <h4 class="fs-sm fw-semibold mb-1">Participante</h4>
                    <p class="mb-0 text-muted fs-xs">
                        {{ $formulario->getParticipanteNombreCompleto() }}<br>
                        {{ $formulario->getParticipanteTipoYDocumento() }}
                    </p>
                </div>

                <!-- Información del curso -->
                <div class="mb-4">
                    <h4 class="fs-sm fw-semibold mb-1">Curso inscrito</h4>
                    <p class="mb-0 fs-xs text-muted">
                        <strong>{{ $formulario->getGrupoNombreCurso() }}</strong><br>
                        G: {{ $formulario->getGrupoId() }} — {{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }}<br>
                        Modalidad: {{ $formulario->getGrupoModalidad() }}<br>
                        Periodo: {{ $formulario->getGrupoCalendarioNombre() }}<br>
                        Formulario: {{ $formulario->getNumero() }}
                    </p>
                </div>

                <!-- Selección de convenio -->
                <div class="row g-3">
                    <!-- No aplica -->
                    <div class="col-md-4 col-sm-6">
                        <div class="form-check form-block border p-2 h-100">
                            <input type="radio" class="form-check-input" id="convenio-0" value="0@0@No aplica" name="convenio" checked>
                            <label class="form-check-label fs-xs" for="convenio-0">
                                <span class="d-block fw-normal p-1">
                                    <span class="d-block fw-semibold mb-1">No aplica</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Convenios disponibles -->
                    @foreach ($convenios as $convenio)
                        @if ($convenio->esVigente())
                            @php
                                $checked = ($convenio->getId() == $formulario->getParticipanteIdBeneficioConvenio()) ? 'checked' : '';
                                $tagColor = 'bg-danger';
                                if ($convenio->getDescuento() >= 50) {
                                    $tagColor = 'bg-success';
                                } elseif ($convenio->getDescuento() >= 20) {
                                    $tagColor = 'bg-warning';
                                }
                            @endphp
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check form-block border p-2 h-100">
                                    <input type="radio" class="form-check-input" {{ $checked }} id="convenio-{{ $convenio->getId() }}" value="{{ $convenio->getId().'@'.$convenio->getDescuento().'@'.$convenio->getNombre() }}" name="convenio">
                                    <label class="form-check-label fs-xs text-truncate" for="convenio-{{ $convenio->getId() }}">
                                        <span class="d-block fw-normal p-1">
                                            <span class="badge {{ $tagColor }} text-white">{{ $convenio->getDescuento() }}%</span>
                                            <span class="d-block fw-semibold mb-1 mt-2">{{ $convenio->getNombre() }}</span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Botón de guardar -->
            <div class="block-content block-content-full">
                <button type="submit" class="btn btn-primary w-100 py-3 fs-xs">
                    <i class="fa fa-check opacity-50 me-1"></i> Guardar Convenio Seleccionado
                </button>
            </div>
        </div>
    </div>
</div>
