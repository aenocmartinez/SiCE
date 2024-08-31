<input type="hidden" name="estado" value="Revisar comprobante de pago">
<input type="hidden" name="medioPago" value="pagoEcollect">
<input type="hidden" name="flagComprobante" value="flagComprobante">

<div class="container pt-0 pb-5">
    <!-- Encabezado del Resumen -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-primary fs-4 mb-0">Resumen de tu Compra</h1>
            <p class="text-muted fs-xs">{{ $participante->getNombreCompleto() }} | {{ $participante->getDocumentoCompleto() }}</p>
        </div>
    </div>

    <div class="row">
        <!-- Resumen de Cursos -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-header bg-white text-primary fs-xs" style="border-radius: 20px 20px 0 0;">
                    Detalles de los Cursos
                </div>
                <div class="table-responsive">
                    <table class="table align-middle fs-xs mb-0" style="border-radius: 0 0 20px 20px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="fw-semibold">Curso</th>
                                <th class="fw-semibold text-center">Costo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_a_pagar = 0;
                            @endphp

                            @foreach (Session::get('cursos_a_matricular') as $curso)   
                            <tr>
                                <td class="p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center me-3" style="width: 35px; height: 35px;">
                                            <i class="fa fa-book"></i>
                                        </div>
                                        <div>
                                            <div class="text-dark">{{ 'G'.$curso['grupoId'] }} - {{ $curso['nombre_curso'] }}</div>
                                            <span class="text-muted fs-xs">{{ $curso['dia'] }} / {{ $curso['jornada'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-success p-3">{{ $curso['totalPagoFormateado'] }}</td>
                            </tr>

                            @php
                                $total_a_pagar += $curso['totalPago']; 
                            @endphp

                            @endforeach
                            <!-- Total Row -->
                            <tr class="bg-light">
                                <td class="text-end fw-bold fs-sm p-3">Total a pagar</td>
                                <td class="text-center text-success fw-bold fs-sm p-3">
                                    {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($total_a_pagar) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sección de Pago y Carga de Comprobante -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0" style="border-radius: 20px;">
                <div class="card-body d-flex flex-column justify-content-between position-relative">
                    <!-- Ícono de Ayuda centrado -->
                    <div class="text-center mb-2">
                        <button class="btn btn-outline-danger rounded-circle fs-xs p-2 animate-bounce" id="helpButton" title="Ayuda para el pago" type="button">
                            <i class="fa fa-question-circle" style="font-size: 1.5rem;"></i>
                        </button>
                    </div>

                    <!-- Paso 1: Realizar Pago en Línea -->
                    <div class="text-center mb-3">
                        <h4 class="fs-5 text-primary mb-2">Paso 1: Realiza tu pago en línea</h4>
                        <button type="button" class="btn btn-success btn-lg rounded-pill fs-xs px-4 w-100" onclick="confirmEcollect();">
                            <i class="fa fa-credit-card me-2"></i> Realizar pago en línea
                        </button>
                        <div class="mt-2">
                            <a href="{{ asset('biblioteca/Instructivo_pago_en_linea_2.pdf') }}" target="_blank" class="text-info fs-xs">
                                <i class="fa fa-download me-1"></i> Consulta el instructivo para pagos en línea
                            </a>
                        </div>
                    </div>

                    <!-- Separador Visual -->
                    <div class="my-2 text-center position-relative">
                        <hr class="my-2">
                    </div>

                    <!-- Paso 2: Cargar Comprobante -->
                    <div class="text-center mt-3">
                        <h4 class="fs-5 text-primary mb-2">Paso 2: Carga tu comprobante de pago</h4>
                        <div class="mb-3">
                            <div class="input-group">
                                <label class="input-group-text rounded-start fs-xs" for="pdf">
                                    <i class="fa fa-upload me-2"></i> PDF
                                </label>
                                <input type="file" name="pdf" class="form-control rounded-end fs-xs @error('pdf') is-invalid @enderror" id="pdf" accept=".pdf">
                            </div>
                            @error('pdf')
                                <span class="invalid-feedback d-block mt-2 fs-xs" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Botón Confirmar Inscripción -->
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fs-xs px-4 w-100 mt-3">
                            <i class="fa fa-check-circle me-2"></i> Confirmar inscripción
                        </button>
                    </div>

                    <!-- Tooltip de Ayuda -->
                    <div id="helpTooltip" class="d-none position-absolute bg-white border shadow-sm p-3 rounded fs-xs text-start" style="top: -200px; right: 50%; transform: translateX(50%); z-index: 1000; width: 250px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-danger mb-0">¡Importante!</h6>
                            <button class="btn btn-link p-0 text-dark" id="closeTooltip" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <p class="mb-3 text-dark">Para completar tu inscripción, sigue estos pasos:</p>
                        <ol class="text-start ps-3 mb-1 text-dark">
                            <li>Realiza el pago en línea.</li>
                            <li>Regresa a esta página y sube el comprobante de pago en formato PDF.</li>
                            <li>Haz clic en el botón Confirmar inscripción.</li>
                        </ol>
                        <p class="mb-1 mt-3 text-dark">La verificación de tu inscripción comenzará una vez que hayas cargado el comprobante y tomará 3 días hábiles.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Añadir animación al ícono de ayuda
        setInterval(function() {
            $('#helpButton').toggleClass('animate-bounce');
        }, 1500);

        // Mostrar/Ocultar el tooltip
        $('#helpButton').on('click', function() {
            $('#helpTooltip').toggleClass('d-none');
        });

        // Cerrar el tooltip
        $('#closeTooltip').on('click', function() {
            $('#helpTooltip').addClass('d-none');
        });
    });
</script>

<style>
    .animate-bounce {
        animation: bounce 1s infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
</style>
