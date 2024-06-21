
    <div class="row">
        <div class="col-lg-6 col-xl-6">
            <div class="mb-4 fs-xs">
                <select class="js-select2 form-select @error('area_id') is-invalid @enderror" id="area_id" name="area_id" style="width: 100%;" data-placeholder="Selecciona un área...">
                <option></option>
                @foreach ($areas as $area)            
                <option value="{{ $area->getId() }}" {{ old('area_id') == $area->getId() ? 'selected' : '' }}>{{ $area->getNombre() }}</option>
                @endforeach
                </select>
                @error('area_id')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                
            </div>
        </div>

        <div class="col-lg-6 col-xl-6">
            <div class="mb-4 fs-xs">
                <select class="js-select2 form-select" id="nuevo_curso" name="nuevo_curso" style="width: 100%;" data-placeholder="Selecciona un curso...">
                <option></option>
                </select>
            </div>
        </div>    
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12 text-center" id="saldo-a-favor" style="display:none;">
            <p class="fs-xs">Tiene un saldo a favor de: </p>
            <h4 class="text-success" style="margin-top: -15px;" id="valor-saldo-a-favor"></h4>
            <p class="fs-xs" style="margin-top: -15px;">
                El curso que piensa tomar tiene un costo menor al valor pagado. <br>
                Al realizar el cambio en el sistema se hará la devolución automáticamente. <br>
                Posteriormente podrá consultar para informar a financiera.
                <!-- ¿desea la devolución del dinero o abonarlo para un próximo periodo? -->
                <br><br>
                <input type="hidden" name="decision_sobre_pago" id="decision_sobre_pago" value="devolución">
                <!-- <input type="radio" name="decision_sobre_pago" id="decision_sobre_pago" value="devolución"> Devolución del dinero -->
                <!-- <input type="radio" name="decision_sobre_pago" id="decision_sobre_pago" value="abono"> Abono próximo curso -->
            </p>
            
        </div>
        <div class="col-lg-12 col-xl-12" id="saldo-en-contra" style="display:none;">
            <p class="fs-xs text-center">
                Tiene un <strong class="text-danger">saldo en contra</strong> dado que el curso seleccionado tiene un costo mayor. 
                Su inscripción pasará a estado pendiente de pago y deberá realizar el pago restante para legalizar la matrícula. 
                <h4 class="text-danger text-center mt-1" id="valor-saldo-en-contra"></h4>
            </p>            
        </div>        
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12 text-center" id="seccion_nuevo_valor_a_pagar">
            <button class="btn btn-large btn-success mb-3">Realizar cambio de curso o grupo</button>
        </div>
    </div>
