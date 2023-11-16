@extends("plantillas.principal")

@php
    $titulo = "Paso 1: verificar existencia del participante";
@endphp

@section("title", $titulo)
@section("description", "")

@section("content")

<div class="block block-rounded">

    <div class="block-content">

        <div class="row push">

            <form class="row row-cols-lg-auto g-3 align-items-center" action="{{ route('formulario-inscripcion.buscar_participante_por_documento')}}" method="POST">
            @csrf
                <div class="col-12">
                    <label class="visually-hidden" for="tipoDocumento">Tipo documento</label>
                    <select class="form-select @error('tipoDocumento') is-invalid @enderror" id="tipoDocumento" name="tipoDocumento">            
                        <option value=""> Selecciona una opción </option>
                        <option value="CC" {{ old('tipoDocumento') == 'CC' ? 'selected' : '' }}>Cédula</option>
                        <option value="TI" {{ old('tipoDocumento') == 'TI' ? 'selected' : '' }}>Tarjeta de identidad</option>
                        <option value="CE" {{ old('tipoDocumento') == 'CE' ? 'selected' : '' }}>Cédula de extranjería</option>
                        <option value="PP" {{ old('tipoDocumento') == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                    </select>
                    @error('tipoDocumento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror  
                </div>
                <div class="col-12">
                    <label class="visually-hidden" for="documento">Documento</label>
                    <input type="text" 
                    class="form-control @error('documento') is-invalid @enderror"
                    id="documento" 
                    name="documento" 
                    placeholder="Documento" 
                    value="{{ old('documento') }}"                
                    >
                    @error('documento')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror 

                </div>
                <div>
                    <button type="submit" class="btn btn-large btn-info">Buscar participante</button>
                </div>
            </form>

        </div>

    </div>

</div>

@endsection