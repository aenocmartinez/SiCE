
<input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre', $curso['nombre']) }}">
{!! $errors->first('nombre', '<br><small>:message</small>') !!}
<br><br>

@php
    $areaId = isset($curso['areaId']) ? $curso['areaId'] : 0;
    $checked = isset($curso['modalidad']) && $curso['modalidad'] == 'virtual' ? 'checked' : '';
@endphp


<select name="area">    
    <option value="">Selecciona un área</option>
    @foreach ($curso['areas'] as $area)
    <option value="{{ $area['id'] }}" {{ $areaId == $area['id'] ? 'selected' : '' }} >{{ $area['nombre'] }}</option>
    @endforeach
</select>
{!! $errors->first('area', '<br><small>:message</small>') !!}
<br><br>

<input type="text" name="costo" placeholder="costo" value="{{ old('costo', $curso['costo']) }}">
{!! $errors->first('costo', '<br><small>:message</small>') !!}
<br><br>

<div>
    <input type="checkbox" id="modalidad" name="modalidad" {{ $checked }} />
    <label for="scales">Modalidad virtual</label>
</div>
<br><br>

<button>{{ $btnText }}</button>

<a href="{{ route('cursos.index') }}"> Cancelar</a>