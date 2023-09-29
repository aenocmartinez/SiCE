@extends("plantillas.principal")

@section("title", "Editar orientador | " . env("APP_NAME"))

@section("content")

    <h1>Editar orientador</h1>

    <form method="post" action="{{route('orientadores.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $orientador['id'] }}">
        
        @include('orientadores._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection