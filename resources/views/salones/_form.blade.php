
@php
    $checked = 'checked';
    if (isset($salon['id'])) {
        $checked = $salon['disponible'] ? 'checked' : '';
    }
    
@endphp

<input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre', $salon['nombre']) }}">
{!! $errors->first('nombre', '<br><small>:message</small>') !!}
<br><br>

<input type="number" name="capacidad" min="1" placeholder="Capacidad" value="{{ old('capacidad', $salon['capacidad']) }}">
{!! $errors->first('capacidad', '<br><small>:message</small>') !!}
<br><br>

<div>
    <input type="checkbox" id="disponible" name="disponible" {{ $checked }} />
    <label for="disponible">Disponible</label>
</div>
<br><br>

<button>{{ $btnText }}</button>

<a href="{{ route('salones.index') }}"> Cancelar</a>