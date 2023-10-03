<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

                <label class="form-label" for="curso">Curso</label>
                <select class="form-select @error('curso') is-invalid @enderror" id="curso" name="curso">
                    <option value="">Selecciona un curso</option>
                    @foreach ($cursos as $curso)
                        <option 
                            value="{{ $curso->getId() }}" 
                            {{ old('curso', $grupo->getCurso()->getId()) == $curso->getId() ? 'selected' : '' }}
                            >{{ $curso->getNombre() }}</option>
                    @endforeach
                </select>
                @error('curso')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                   
                <br>

                <label class="form-label" for="salon">Salón</label>
                <select class="form-select @error('salon') is-invalid @enderror" id="salon" name="salon">
                    <option value="">Selecciona un salón</option>
                    @foreach ($salones as $salon)
                        <option 
                            value="{{ $salon->getId() }}"
                            {{ old('salon', $grupo->getSalon()->getId()) == $salon->getId() ? 'selected' : '' }}
                            >{{ $salon->getNombre() }}</option>
                    @endforeach
                </select>
                @error('salon')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                 

                <br>

                <label class="form-label" for="jornada">Jornada</label>
                <select class="form-select @error('jornada') is-invalid @enderror" id="jornada" name="jornada">
                    <option value="">Selecciona una jornada</option>
                    @foreach ($jornadas as $jornada)
                        <option 
                            value="{{ $jornada }}"
                            {{ old('jornada', $grupo->getJornada()) == $jornada ? 'selected' : '' }}
                            >{{ $jornada }}</option>
                    @endforeach
                </select>    
                @error('jornada')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                                 

            </div>

            <div class="col-6">

                <label class="form-label" for="calendario">Calendario</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                    <option value="">Selecciona un calendario</option>
                    @foreach ($calendarios as $calendario)
                        @if ($calendario->esVigente())                            
                            <option 
                                value="{{ $calendario->getId() }}"
                                {{ old('calendario', $grupo->getCalendario()->getId()) == $calendario->getId() ? 'selected' : '' }}
                                >{{ $calendario->getNombre() }}
                            </option>
                        @endif
                    @endforeach
                </select>
                @error('calendario')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                  

                <br>

                <label class="form-label" for="dia">Día</label>
                <select class="form-select @error('dia') is-invalid @enderror" id="dia" name="dia">
                    <option value="">Selecciona un día</option>
                    @foreach ($dias as $dia)
                        <option 
                            value="{{ $dia }}"
                            {{ old('dia', $grupo->getDia()) == $dia ? 'selected' : '' }}
                            >{{ $dia }}</option>
                    @endforeach
                </select>   
                @error('dia')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                   
                
                <br>

                <label class="form-label" for="orientador">Orientador</label>
                <select class="form-select @error('orientador') is-invalid @enderror" id="orientador" name="orientador">
                    <option value="">Selecciona un orientador</option>
                    @foreach ($orientadores as $orientador)
                        <option 
                            value="{{ $orientador->getId() }}"
                            {{ old('orientador', $grupo->getOrientador()->getId()) == $orientador->getId() ? 'selected' : '' }}
                            >{{ $orientador->getNombre() }}</option>
                    @endforeach
                </select>   
                @error('orientador')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                              

            </div>

            <div class="col-12 mt-4">
                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('grupos.index') }}" class="btn btn-large btn-light"> Cancelar</a>
            </div>

        </div>
    
    </div>

</div>    


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/flatpickr/flatpickr.min.js')}}"></script>


<script>One.helpersOnLoad(['js-flatpickr']);</script>