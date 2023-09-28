@extends("plantillas.principal")

@section("title", "Editar curso | " . env("APP_NAME"))

@section("content")

    <h1>Editar curso</h1>

    <form method="post" action="{{route('cursos.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $curso['id'] }}">
        
        @include('cursos._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection