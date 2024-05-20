@extends('plantillas.publico')

@section('nameSection', 'Paso 2: Actualiza tus datos personales')

@section('description')
    Tenga ne cuenta diligenciar todos los campos obligatorios.
@endsection


@section('content')

<form method="POST" action="{{ route('public.guardar-datos-participante') }}">
    @csrf

    @include('public._form_actualizacion_datos')   

    <div class="text-end">            
        <button type="submit" class="btn btn-primary mt-3" data-toggle="click-ripple">
            <i class="fa fa-fw fa-arrow-right-to-bracket me-1 opacity-50"></i>
            Guardar y continuar
        </button>
    </div>    
</form>

@endsection