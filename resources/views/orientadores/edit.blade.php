@extends("plantillas.principal")

@section("title", "Editar orientador")

@section("seccion", "Orientadores")
@section("subseccion", "editar orientador")

@section("content")

    <form method="post" action="{{route('orientadores.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $orientador['id'] }}">
        
        @include('orientadores._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection