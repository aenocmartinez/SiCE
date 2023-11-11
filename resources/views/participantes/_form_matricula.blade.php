<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <h5 class="fw-light link-fx text-primary-darker">INFORMACIÓN DE MATRÍCULA</h5>

            <div class="col-5">

                <!-- Listado de calendarios -->
                <label class="form-label" for="calendario">Periodo</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                <option value="">Selecciona un periodo</option>
                    @foreach ($calendarios as $calendario)   
                        @if ($calendario->esVigente())          
                            <option value="{{ $calendario->getId() }}"
                            {{ old('calendario', $calendarioId) == $calendario->getId() ? 'selected' : '' }}
                            >{{ $calendario->getNombre() }}</option>
                        @endif
                    @endforeach
                </select>
                @error('calendario')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror 

            </div>

            <div class="col-5">
                
                <!-- Listado de areas -->
                <label class="form-label" for="area">Área</label>
                <select class="form-select @error('area') is-invalid @enderror" id="area" name="area">
                <option value="">Selecciona una área</option>
                    @foreach ($areas as $area)         
                        <option value="{{ $area->getId() }}" {{ old('area', $areaId) == $area->getId() ? 'selected' : '' }}>{{ $area->getNombre() }}</option>
                    @endforeach
                </select>
                @error('area')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror 
                

            </div>   
            
            <div class="col-2">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-large btn-info">Buscar cursos</button>
            </div>

        </div>

        <div class="row push">
            <table class="table table-vcenter mt-2">
                @forelse ($grupos as $grupo)
                <tr>
                    <td class="fs-sm" style="width: 40%;">
                        <p class="fw-normal mb-0">{{ $grupo->getCodigoGrupo() . " - " . $grupo->getNombreCurso() }} ({{ $grupo->getModalidad() }})</p>
                    </td>
                    <td class="fs-xs" style="width: 20%;">
                        <p class="fw-normal mb-0">{{ $grupo->getDia() }} / {{ $grupo->getJornada() }}</p>
                    </td>
                    <td class="fs-xs" style="width: 15%;">
                        <p class="fw-normal mb-0">{{ $grupo->getCostoFormateado() }}</p>
                    </td>
                    <td class="fs-xs" style="width: 20%;">
                        @php
                            $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"; 
                            if ($grupo->getTotalCuposDisponibles() == 0) {
                                $class = "fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-danger-light text-danger"; 
                            }
                        @endphp
                        <span class="{!! $class !!}">
                        Cupos disponibles: {{ $grupo->getTotalCuposDisponibles() }}
                        </span>
                    </td>                    
                    <td class="text-end" style="width: 5%;">
                        <div class="btn-group">
                            @if ($grupo->getTotalCuposDisponibles() > 0)                                
                                <a href="{{ route('areas.edit', $grupo->getId()) }}" 
                                    class="fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">
                                    Inscribirse
                                </a>
                            @endif
                        </div>
                    </td>                    
                </tr>
                @empty
                <tr>
                    <td class="text-center">No hay grupos para mostrar</td>
                </tr>
                @endforelse 
            </table> 
        </div>
    
    </div>

</div>

