@extends("plantillas.principal")

@section("title", "Editar salón")

@section("seccion", "Salones")
@section("subseccion", "editar salón")

@section("content")

    <form method="post" action="{{route('salones.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $salon['id'] }}">
        
        @include('salones._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection