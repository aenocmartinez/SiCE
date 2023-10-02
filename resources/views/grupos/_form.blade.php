<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

                <label class="form-label" for="curso">Curso</label>
                <select class="form-select @error('curso') is-invalid @enderror" id="curso" name="curso">
                    <option value="">Selecciona un curso</option>
                    @foreach ($cursos as $curso)
                        <option value="{{ $curso->getId() }}" >{{ $curso->getNombre() }}</option>
                    @endforeach
                </select>
                <br>

                <label class="form-label" for="salon">Salón</label>
                <select class="form-select @error('salon') is-invalid @enderror" id="salon" name="salon">
                    <option value="">Selecciona un salón</option>
                    @foreach ($salones as $salon)
                        <option value="{{ $salon->getId() }}" >{{ $salon->getNombre() }}</option>
                    @endforeach
                </select>                

            </div>

            <div class="col-6">

                <label class="form-label" for="calendario">Calendario</label>
                <select class="form-select @error('calendario') is-invalid @enderror" id="calendario" name="calendario">
                    <option value="">Selecciona un calendario</option>
                    @foreach ($calendarios as $calendario)
                        @if ($calendario->esVigente())                            
                            <option value="{{ $calendario->getId() }}" >{{ $calendario->getNombre() }}</option>
                        @endif
                    @endforeach
                </select>

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