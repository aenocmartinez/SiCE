@extends("plantillas.principal")

@section("title", "Editar curso")

@section("seccion", "Cursos")
@section("subseccion", "editar curso")

@section("content")

    <form method="post" action="{{route('cursos.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $curso['id'] }}">
        
        @include('cursos._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection