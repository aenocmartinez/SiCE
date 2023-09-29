@extends("plantillas.principal")

@section("title", "Editar área")

@section("seccion", "Áreas")
@section("subseccion", "editar área")

@section("content")

    <form method="post" action="{{route('areas.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $area['id'] }}">
        
        @include('areas._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection