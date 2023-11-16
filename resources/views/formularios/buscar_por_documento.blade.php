@extends("plantillas.principal")

@php
    $titulo = "Paso 1: verificar existencia del participante";
@endphp

@section("title", $titulo)
@section("description", "")

@section("content")


<div class="row">

    <div class="block block-rounded block-content">

        <form class="row row-cols-lg-auto align-items-center pb-3" action="{{ route('formulario-inscripcion.buscar_participante_por_documento')}}" method="POST">
                @csrf
                <div class="col-xl-4">
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

                <div class="col-xl-4">
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

                <div class="col-xl-4">
                    <button type="submit" class="btn btn-large btn-info">
                        <i class="fa fa-search me-1 opacity-50"></i>
                        Buscar participante
                    </button>
                </div>

        </form>

    </div>

</div>

@endsection