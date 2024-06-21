<div class="row">
    <div class="col-md-6 col-xl-6">
        <div class="block block-rounded block-fx-shadow">
        <div class="block-header block-header-default">
            <h3 class="block-title">Datos generales</h3>
        </div>
        <div class="block-content">
            <table class="table table-bordered fs-xs">
            <tr>
                    <td style="width: 40%;">Participante</td>
                    <td>
                        {{ $formulario->getParticipanteNombreCompleto() }}<br>
                        {{ $formulario->getParticipanteTipoYDocumento() }}
                    </td>
                </tr>                
                <tr>
                    <td>Formulario</td>
                    <td>{{ $formulario->getNumero() }}</td>
                </tr>
                <tr>
                    <td>Curso</td>
                    <td>
                        {{ $formulario->getGrupoNombreCurso() }}
                        <br>
                        <small>                            
                            {{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }} <br>
                            {{ $formulario->getGrupoNombreId() }}
                        </small>
                    </td>
                </tr>        
                <tr>
                    <td>Estado</td>
                    <td>{{ $formulario->getEstado() }}</td>
                </tr>                
                <tr>
                    <td>Costo del curso</td>
                    <td>{{ $formulario->getGrupoCursoCosto() }}</td>
                </tr>
                <tr>
                    <td>Convenio</td>
                    <td>{{ strlen($formulario->getConvenioNombre()) == 0 ? 'N/A' : $formulario->getConvenioNombre() }}</td>
                </tr> 
                <tr>
                    <td>Descuento por convenio</td>
                    <td>{{ $formulario->getValorDescuentoFormateado() }}</td>
                </tr>                                
                <tr>
                    <td>{{ ($formulario->getEstado() == 'Pagado') ? 'Total pagado' : 'Total a pagar' }}</td>
                    <td>{{ $formulario->getTotalAPagarFormateado() }}</td>
                </tr>
                <tr>
                    <td>Acción</td>
                    <td>{{ $labelMotivo }}</td>
                </tr>
            </table>
        </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-6">
        <div class="block block-rounded block-fx-pop">
            <div class="block-header block-header-default">
                <h3 class="block-title">Justificación</h3>
            </div>
            <div class="block-content">

                <div class="form-floating mb-4">
                    <textarea class="form-control @error('justificacion') is-invalid @enderror fs-xs" id="justificacion" name="justificacion" style="height: 200px" placeholder="Escribe la justificación...">{{ old('justificacion') }}</textarea>
                    @error('justificacion')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-12 col-xl-12">
      
        <div class="block block-rounded block-fx-shadow">

            <div class="block-content">

                @include('cambios-traslados.' . $vista_segun_motivo)
            </div>

        </div>
    </div>

</div>