@extends("plantillas.principal")

@php
    $titulo = "Aplazamiento";    
@endphp

@section("title", $titulo)    

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

    <form action="{{ route('cambios_traslados.aplazar_inscripcion') }}" method="POST" class="mb-5">
        @csrf
        <input type="hidden" name="numero_formulario" id="numero_formulario" value="{{ $formulario->getNumero() }}">
        <input type="hidden" name="formulario_id" id="formulario_id" value="{{ $formulario->getId() }}">
        <input type="hidden" name="saldo_a_favor" id="saldo_a_favor" value="{{ $formulario->TotalPagoRealizado() }}">
        <input type="hidden" name="participante_id" id="participante_id" value="{{ $formulario->getParticipanteId() }}">
        <input type="hidden" name="calendario_id" id="calendario_id" value="{{ $periodo->getId() }}">        
        <input type="hidden" name="accion" id="accion" value="aplazamiento">

        <!-- Empieza aqui lo propio de aplazamiento -->
        <div class="container-fluid py-4" style="background-color: #f8f9fa;">

            <div class="row">
                <!-- Datos Generales -->
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4" style="background-color: #ffffff; border-radius: 8px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3" style="background-color: #3a6eb8; padding: 10px; border-radius: 5px;">                                
                                <h5 class="card-title ml-2 fw-bold text-white">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                    Datos Generales
                                </h5>
                            </div>
                            <ul class="list-unstyled fs-xs mb-0" style="color: #2c3e50; line-height: 1.6;">
                                <li class="mb-3">
                                    <strong>Participante:</strong><br>
                                    {{ $formulario->getParticipanteNombreCompleto() }}<br>
                                    <span class="text-muted">{{ $formulario->getParticipanteTipoYDocumento() }}</span>
                                </li>
                                <li class="mb-3">
                                    <strong>Formulario:</strong><br>
                                    {{ $formulario->getNumero() }}
                                </li>
                                <li class="mb-3">
                                    <strong>Curso Actual:</strong><br>
                                    {{ $formulario->getGrupoNombreCurso() }}<br>
                                    <span class="text-muted">{{ $formulario->getGrupoDia() }} / {{ $formulario->getGrupoJornada() }}</span>
                                </li>
                                <li class="mb-3">
                                    <strong>Costo del Curso:</strong><br>
                                    {{ $formulario->getGrupoCursoCosto() }}
                                </li>
                                <li class="mb-3">
                                    <strong>Convenio:</strong><br>
                                    {{ strlen($formulario->getConvenioNombre()) == 0 ? 'N/A' : $formulario->getConvenioNombre() }}
                                </li>
                                <li class="mb-3">
                                    <strong>Descuento:</strong><br>
                                    {{ $formulario->getValorDescuentoFormateado() }}
                                </li>
                                <li>
                                    <strong>{{ ($formulario->getEstado() == 'Pagado') ? 'Total Pagado' : 'Total a Pagar' }}:</strong><br>
                                    {{ $formulario->getTotalAPagarFormateado() }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Justificación y Aplazamiento-->
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4" style="background-color: #ffffff; border-radius: 8px;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3" style="background-color: #3a6eb8; padding: 10px; border-radius: 5px;">                                
                                <h5 class="card-title ml-2 fw-bold text-white">
                                    <i class="fas fa-history fa-lg"></i>
                                    Aplazamiento
                                </h5>
                            </div>
                            
                            
                            <!-- Justificación del Cambio -->
                            <div class="mb-3">
                                <label for="justificacion" class="form-label fs-xs" style="color: #2c3e50; font-weight: bold;">Justificación:</label>
                                <textarea class="form-control @error('justificacion') is-invalid @enderror fs-xs" id="justificacion" name="justificacion" style="height: 120px; background-color: #ffffff; border-color: #7f8c8d; border-radius: 5px;" placeholder="Escribe la justificación...">{{ old('justificacion') }}</textarea>
                                @error('justificacion')
                                <span class="invalid-feedback" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                            </div>

                            <!-- Fecha caducidad -->
                            <div class="mb-4 col-md-6">
                            <label for="fec_caducidad" class="form-label fs-xs @error('fec_caducidad') is-invalid @enderror" style="color: #2c3e50; font-weight: bold;">Aplazamiento válido hasta:</label>
                                <input type="text" 
                                    class="js-flatpickr form-control fs-xs" 
                                    id="fec_caducidad" 
                                    name="fec_caducidad" 
                                    value="{{ old('fec_caducidad', $fec_caducidad) }}"
                                    placeholder=""
                                    style="background-color: #ffffff; border-color: #7f8c8d; border-radius: 5px;"
                                    title="Fecha de caducidad">
                                    @error('fec_caducidad')
                                        <span class="invalid-feedback" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror                                    
                            </div>                          

                            <div class="text-left mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Finaliza aqui lo propio de cambio de cursos -->
        
    </form>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script>One.helpersOnLoad(['js-flatpickr']);</script>

@endsection
