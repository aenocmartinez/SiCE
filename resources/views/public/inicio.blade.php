@extends('plantillas.publico')


@section('content')
<div class="col-sm-8 col-xl-4">
    <form method="post" action="{{ route('public.consultar-existencia') }}">
        @csrf
        <div class="mb-4">
            <select name="tipoDocumento" id="tipoDocumento" class="form-control py-3 text-center @error('tipoDocumento') is-invalid @enderror">
                <option value="CC" {{ old('tipoDocumento') == "CC" ? 'selected' : '' }} selected>Cédula de ciudadanía</option>
                <option value="TI" {{ old('tipoDocumento') == "TI" ? 'selected' : '' }}>Tarjeta de identidad</option>
                <option value="CE" {{ old('tipoDocumento') == "CE" ? 'selected' : '' }}>Cédula de extranjería</option>
                <option value="PP" {{ old('tipoDocumento') == "PP" ? 'selected' : '' }}>Pasaporte</option>
            </select>
            @error('tipoDocumento')
                <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
            @enderror
            <br>
            <input type="text" class="form-control py-3 text-center @error('documento') is-invalid @enderror" id="documento" name="documento" placeholder="Número de documento">
            @error('documento')
                <span class="invalid-feedback text-center" role="alert">
                    {{ $message }}
                </span>
            @enderror        
        </div>
        <div class="text-center">
   
            <button type="submit" class="btn btn-outline-primary" data-toggle="click-ripple">
                <i class="fa fa-fw fa-magnifying-glass me-1 opacity-50"></i>
                Continuar
            </button>
        </div>
    </form>
</div>
@endsection