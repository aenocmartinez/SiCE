@extends("plantillas.principal")

@section("title", "Nuevo orientador | " . env("APP_NAME"))

@section("content")

    <h1>Nuevo orientador</h1>

    <form method="post" action="{{ route('orientadores.store') }}">
        @csrf
        
        @include('orientadores._form', ['btnText' => 'Guardar'])

    </form>
@endsection