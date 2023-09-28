
<input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre', $area['nombre']) }}">
{!! $errors->first('nombre', '<br><small>:message</small>') !!}
<br>

<button>{{ $btnText }}</button>

<a href="{{ route('areas.index') }}"> Cancelar</a>