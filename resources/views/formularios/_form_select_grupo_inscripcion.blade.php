<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

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
            <table class="table table-vcenter mt-4">
                @forelse ($grupos as $grupo)
                <tr>
                    <td class="fs-sm" style="width: 40%;">
                        <p class="fw-normal mb-0">
                            {{ $grupo->getCodigoGrupo() . " - " . $grupo->getNombreCurso() }} ({{ $grupo->getModalidad() }})
                            <br>
                            <span style="font-size: 12px">
                                Orientador/a: {{ $grupo->getNombreOrientador() }}
                            </span>
                        </p>
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
                        <!-- Formulario -->
                        @if ($grupo->getTotalCuposDisponibles() > 0)
                        <a href="{{ route('formulario-inscripcion.paso-4', [$participante->getId(), $grupo->getId()]) }}" 
                                class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                data-bs-toggle="tooltip" 
                                title="Seleccionar">
                                Seleccionar
                        </a>                        
                        @endif
                        <!-- Fin formulario -->
                    </td>                    
                </tr>
                @empty
                    @if (!$calendario->existe())                    
                        <tr>
                            <td class="text-center">No hay grupos para mostrar</td>
                        </tr>
                    @endif
                @endforelse 
            </table> 
        </div>
    
    </div>

</div>

