@php
    $checked = '';
    if ($convenio->existe()) {
        $checked = $convenio->esCooperativa() ? 'checked' : '';
    }
@endphp

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

                <label class="form-label" for="nombre">Nombre</label>
                <input type="text" 
                    class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Nombre" 
                    value="{{ old('nombre', $convenio->getNombre()) }}"
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                <br>

                <label class="form-label" for="calendario">Periodo</label>
                <input type="hidden" name="calendario" value="{{ $convenio->getCalendarioId() }}">
                <input type="text" 
                       class="form-control" 
                       name="periodo" 
                       id="periodo" 
                       disabled
                       value="{{ $convenio->existeCalendarioVigente() ? $convenio->getNombreCalendario() : 'No existe periodo académico vigente' }}">

                <br> 
                
                <label class="form-label" for="descuento">Descuento</label>
                <input type="number" min="0" step="1"
                    class="form-control @error('descuento') is-invalid @enderror" 
                    id="descuento" 
                    name="descuento" 
                    placeholder="descuento" 
                    value="{{ old('descuento', $convenio->getDescuento()) }}"
                    >
                    @error('descuento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror  
                        
                    <br>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="esCooperativa" name="esCooperativa" {{ $checked }}>
                        <label class="form-check-label" for="disponible">Es una cooperativa</label>
                    </div> 

                    <!-- Reglas de descuento para cooperativas -->
                    <div id="reglas-descuento-wrapper" class="mt-3" style="display: none;">
                        <label class="form-label d-block fs-sm"><strong>Reglas de descuento por número de participantes</strong></label>

                        <div id="reglas-container">
                            @php $reglasOld = old('reglas'); @endphp

                            @if(is_array($reglasOld) && count($reglasOld) > 0)
                                @foreach($reglasOld as $i => $regla)
                                    <div class="row g-2 align-items-center mb-1 regla-row">
                                        <div class="col-4">
                                            <input type="number"
                                                name="reglas[{{ $i }}][min_participantes]"
                                                value="{{ old("reglas.$i.min_participantes") }}"
                                                class="form-control form-control-sm fs-xs @error("reglas.$i.min_participantes") is-invalid @enderror"
                                                placeholder="Mín. participantes"
                                                required>
                                            @error("reglas.$i.min_participantes")
                                                <span class="invalid-feedback d-block fs-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-4">
                                            <input type="number"
                                                name="reglas[{{ $i }}][max_participantes]"
                                                value="{{ old("reglas.$i.max_participantes") }}"
                                                class="form-control form-control-sm fs-xs @error("reglas.$i.max_participantes") is-invalid @enderror"
                                                placeholder="Máx. participantes"
                                                required>
                                            @error("reglas.$i.max_participantes")
                                                <span class="invalid-feedback d-block fs-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-3">
                                            <div class="input-group input-group-sm">
                                                <input type="number"
                                                    name="reglas[{{ $i }}][descuento]"
                                                    value="{{ old("reglas.$i.descuento") }}"
                                                    class="form-control fs-xs @error("reglas.$i.descuento") is-invalid @enderror"
                                                    placeholder="%"
                                                    required step="0.01" min="0" max="100">
                                                <span class="input-group-text fs-xs">%</span>
                                            </div>
                                            @error("reglas.$i.descuento")
                                                <span class="invalid-feedback d-block fs-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-1 text-end">
                                            <button type="button" class="btn btn-sm btn-alt-danger" onclick="eliminarFila(this)">
                                                <i class="fa fa-times fs-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach($reglas as $i => $regla)
                                    <div class="row g-2 align-items-center mb-1 regla-row">
                                        <div class="col-4">
                                            <input type="number"
                                                name="reglas[{{ $i }}][min_participantes]"
                                                value="{{ $regla->getMinParticipantes() }}"
                                                class="form-control form-control-sm fs-xs"
                                                placeholder="Mín. participantes"
                                                required>
                                        </div>
                                        <div class="col-4">
                                            <input type="number"
                                                name="reglas[{{ $i }}][max_participantes]"
                                                value="{{ $regla->getMaxParticipantes() }}"
                                                class="form-control form-control-sm fs-xs"
                                                placeholder="Máx. participantes"
                                                required>
                                        </div>
                                        <div class="col-3">
                                            <div class="input-group input-group-sm">
                                                <input type="number"
                                                    name="reglas[{{ $i }}][descuento]"
                                                    value="{{ $regla->getDescuento() }}"
                                                    class="form-control fs-xs"
                                                    placeholder="%"
                                                    required step="0.01" min="0" max="100">
                                                <span class="input-group-text fs-xs">%</span>
                                            </div>
                                        </div>
                                        <div class="col-1 text-end">
                                            <button type="button" class="btn btn-sm btn-alt-danger" onclick="eliminarFila(this)">
                                                <i class="fa fa-times fs-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>


                        <button type="button" id="agregar-btn" class="btn btn-sm btn-alt-success fs-xs">
                            <i class="fa fa-plus"></i> Agregar nueva regla
                        </button>
                    </div>

                <!-- <label class="form-label" for="fec_ini">Fecha inicial</label>
                <input type="text" 
                       class="js-flatpickr form-control @error('fec_ini') is-invalid @enderror" 
                       id="fec_ini" 
                       name="fec_ini" 
                       placeholder="Y-m-d"
                       value="{{ old('fec_ini', $convenio->getFecInicio()) }}"
                       >
                    @error('fec_ini')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror            

                    <br>
                <label class="form-label" for="fec_fin">Fecha final</label>
                <input type="text" 
                       class="js-flatpickr form-control @error('fec_fin') is-invalid @enderror" 
                       id="fec_fin" 
                       name="fec_fin" 
                       placeholder="Y-m-d"
                       value="{{ old('fec_fin', $convenio->getFecFin()) }}"
                       >
                    @error('fec_fin')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror -->
            </div>

            <!-- Columna 2 -->
            <div class="col-6">
                <label class="form-label" for="comentarios">Comentarios</label>
                <textarea class="form-control fs-xs" id="comentarios" name="comentarios" style="height: 130px">{{ old('comentarios', $convenio->getComentarios()) }}</textarea>
                <br>           
            </div>
            
            <div class="col-12 mt-4">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                @if (!$convenio->haSidoFacturado() && !$convenio->esUCMC())
                @endif
                <a href="{{ route('convenios.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>            

        </div>
    
    </div>

</div>    


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script>One.helpersOnLoad(['js-flatpickr']);</script>

<script>
    let index = {{ is_array(old('reglas')) ? count(old('reglas')) : 1 }};

    function agregarFila() {
        const container = document.getElementById('reglas-container');
        const div = document.createElement('div');
        div.className = 'row g-2 align-items-center mb-1 regla-row';

        div.innerHTML = `
            <div class="col-4">
                <input type="number" name="reglas[${index}][min_participantes]" class="form-control form-control-sm fs-xs" placeholder="Mín. participantes" required>
            </div>
            <div class="col-4">
                <input type="number" name="reglas[${index}][max_participantes]" class="form-control form-control-sm fs-xs" placeholder="Máx. participantes" required>
            </div>
            <div class="col-3">
                <div class="input-group input-group-sm">
                    <input type="number" name="reglas[${index}][descuento]" class="form-control fs-xs" placeholder="%" required step="0.01" min="0" max="100">
                    <span class="input-group-text fs-xs">%</span>
                </div>
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn btn-sm btn-alt-danger" onclick="eliminarFila(this)">
                    <i class="fa fa-times fs-xs"></i>
                </button>
            </div>
        `;

        container.appendChild(div);
        index++;
    }

    function eliminarFila(btn) {
        const row = btn.closest('.regla-row');
        if (row) {
            row.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('esCooperativa');
        const wrapper = document.getElementById('reglas-descuento-wrapper');
        const agregarBtn = document.getElementById('agregar-btn');
        const container = document.getElementById('reglas-container');

        function toggleReglas() {
            if (checkbox.checked) {
                wrapper.style.display = 'block';
            } else {
                wrapper.style.display = 'none';
                if (!@json(old('reglas'))) {
                    // Solo borrar si NO es una recarga con old()
                    container.innerHTML = '';
                    index = 0;
                    agregarFila();
                }
            }
        }

        checkbox.addEventListener('change', toggleReglas);
        agregarBtn.addEventListener('click', agregarFila);

        toggleReglas(); // Ejecutar al cargar la página
    });
</script>
