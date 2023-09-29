<input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre', $orientador['nombre']) }}">
{!! $errors->first('nombre', '<br><small>:message</small>') !!}
<br><br>

@php
    $selectedTD = $orientador['tipoDocumento'] != ""  ? $orientador['tipoDocumento'] : '';
@endphp

<select name="tipoDocumento">
    <option value=""> - </option>
    <option value="CC" {{ $selectedTD == "CC" ? 'selected' : '' }}>CC</option>
    <option value="TI" {{ $selectedTD == "TI" ? 'selected' : '' }}>TI</option>
    <option value="CE" {{ $selectedTD == "CE" ? 'selected' : '' }}>CE</option>
    <option value="PP" {{ $selectedTD == "PP" ? 'selected' : '' }}>PP</option>
</select>
<input type="text" name="documento" placeholder="Documento" value="{{ old('documento', $orientador['documento']) }}">
{!! $errors->first('documento', '<br><small>:message</small>') !!}
<br><br>

<input type="text" name="emailInstitucional" placeholder="Correo institucional" value="{{ old('emailInstitucional', $orientador['emailInstitucional']) }}">
{!! $errors->first('emailInstitucional', '<br><small>:message</small>') !!}
<br><br>

<input type="text" name="emailPersonal" placeholder="Correo personal" value="{{ old('emailPersonal', $orientador['emailPersonal']) }}">
{!! $errors->first('emailPersonal', '<br><small>:message</small>') !!}
<br><br>

<input type="text" name="direccion" placeholder="Dirección" value="{{ old('direccion', $orientador['direccion']) }}">
{!! $errors->first('direccion', '<br><small>:message</small>') !!}
<br><br>

<select name="eps">
<option value="">Selecciona una eps</option>
    @foreach ($orientador['listaEps'] as $eps)            
        <option value="{{ $eps }}" {{ $orientador['eps'] == $eps ? 'selected' : '' }}>{{ $eps }}</option>
    @endforeach
</select>
<br><br>

<label for="observacion">Observaciones</label>
<textarea name="observacion" rows="8" cols="20">{{ old('observacion', $orientador['observacion']) }}</textarea>
{!! $errors->first('observacion', '<br><small>:message</small>') !!}
<br><br>

<select name="area">    
    <option value="">Selecciona un área</option>
    @foreach ($orientador['areas'] as $area)
    <option value="{{ $area['id'] }}">{{ $area['nombre'] }}</option>
    @endforeach
</select>
{!! $errors->first('area', '<br><small>:message</small>') !!}
<br><br>

<button>{{ $btnText }}</button>

<a href="{{ route('orientadores.index') }}"> Cancelar</a>