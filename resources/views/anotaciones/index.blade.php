@extends("plantillas.principal")

@section("title", "Consultar comentarios")
@section("description", "Permite buscar y visualizar los comentarios realizados sobre un formulario de inscripción durante su proceso de gestión.")

@section("content")

<div class="container-fluid py-4">
    <div class="block block-rounded block-content shadow-sm p-4 bg-white">
        <!-- Formulario -->
        <form class="row g-3 align-items-center" action="{{ route('comentarios.buscar') }}" method="POST">
            @csrf
            <div class="col-md-3">
                <label for="periodo" class="form-label">Periodo</label>
                <select class="form-select form-select-sm shadow-sm @error('periodo') is-invalid @enderror" id="periodo" name="periodo">
                    <option value="">Selecciona periodo</option>
                    @foreach ($periodos as $p)
                        <option value="{{ $p->getId() }}" {{ old('periodo') == $p->getId() ? 'selected' : '' }}>
                            {{ $p->getNombre() }}
                        </option>
                    @endforeach
                </select>
                @error('periodo')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                 
            </div>

            <div class="col-md-3">
                <label for="tipo_documento" class="form-label">Tipo de documento</label>
                <select class="form-select form-select-sm shadow-sm @error('tipo_documento') is-invalid @enderror" id="tipo_documento" name="tipo_documento">
                    <option value="">Selecciona tipo documento</option>
                    @foreach ($tipos_de_documento as $tipo_documento)
                        <option value="{{ $tipo_documento['value'] }}" {{ old('tipo_documento') == $tipo_documento['value'] ? 'selected' : '' }}>
                        {{ $tipo_documento['nombre'] }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_documento')
                    <span class="invalid-feedback" role="alert">
                        {{ $message }}
                    </span>
                @enderror                
            </div>            

            <div class="col-md-4">
                <label for="documento" class="form-label">Número documento</label>
                <input type="text" 
                        name="documento" 
                        id="documento" 
                        value="{{ old('documento') }}" 
                        class="form-control form-control-sm shadow-sm @error('documento') is-invalid @enderror" 
                        placeholder="Ingrese documento">
                        @error('documento')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
            </div>
            
            <div class="col-md-2 text-end">
                <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm mt-4">
                    <i class="fa fa-search me-1"></i> Buscar
                </button>
            </div>
        </form>


    </div>
</div>

@endsection
