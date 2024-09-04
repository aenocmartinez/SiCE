@extends("plantillas.principal")

@php
    $titulo = "Iniciar un nuevo trámite";    
@endphp

@section("title", $titulo)

@section("description", "En este formulario realice un cambio, un traslado, un aplazamiento o una devolución.")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

        
<div class="row">

    <div class="block block-rounded block-content">

        <form class="row row-cols-lg-auto align-items-center pb-3" action="{{ route('cambios-y-traslados.buscar_participante_por_documento')}}" method="POST">
                @csrf
                <div class="col-xl-4">
                    <label class="visually-hidden" for="tipoDocumento">Tipo documento</label>
                    <select class="form-select @error('tipoDocumento') is-invalid @enderror" id="tipoDocumento" name="tipoDocumento">
                        @foreach ($tipo_documentos as $tipo)                        
                            <option value="{{ $tipo['value'] }}" {{ old('tipoDocumento') == $tipo['value'] ? 'selected' : ''}}>{{ $tipo['nombre'] }}</option>                        
                        @endforeach
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