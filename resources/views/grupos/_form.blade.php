<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <div class="col-6">

            <label class="form-label" for="curso">Curso</label>
                <select class="form-select @error('curso') is-invalid @enderror" id="curso" name="curso">
                    <option value="">Selecciona un curso</option>
                    @foreach ($cursos as $curso)
                        <option value="{{ $curso['id'] }}" >{{ $curso['nombre'] }}</option>
                    @endforeach
                </select>            

            </div>

            <div class="col-6">

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