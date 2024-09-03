<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded">
                <div class="card-header bg-gradient-primary text-white text-center py-3">
                    <h4 class="mb-0">Registrar Aplazamiento</h4>
                </div>
                <div class="card-body p-5">
                    <form action="/ruta-para-guardar-aplazamiento" method="POST">
                        <!-- Campo oculto para ID del Participante -->
                        <input type="hidden" name="participante_id" value="ID_DEL_PARTICIPANTE">

                        <!-- Campo Saldo a Favor -->
                        <div class="form-group mb-4">
                            <label for="saldo_a_favor" class="form-label fw-bold">Saldo a Favor</label>
                            <input type="number" class="form-control border-secondary shadow-sm" id="saldo_a_favor" name="saldo_a_favor" step="0.01" placeholder="Ingresa el saldo a favor...">
                            <!-- Indicador de error -->
                            <small class="text-danger d-none" id="error-saldo_a_favor">Mensaje de error aquí</small>
                        </div>

                        <!-- Campo Fecha de Caducidad -->
                        <div class="form-group mb-4">
                            <label for="fecha_caducidad" class="form-label fw-bold">Fecha de Caducidad</label>
                            <input type="date" class="form-control border-secondary shadow-sm" id="fecha_caducidad" name="fecha_caducidad">
                            <!-- Indicador de error -->
                            <small class="text-danger d-none" id="error-fecha_caducidad">Mensaje de error aquí</small>
                        </div>

                        <!-- Checkbox Redimido -->
                        <div class="form-group form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="1" id="redimido" name="redimido">
                            <label class="form-check-label" for="redimido">
                                <strong>Redimido</strong>
                            </label>
                        </div>

                        <!-- Botón de Envío -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill shadow-sm">Registrar Aplazamiento</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
