@extends("plantillas.principal")

@section("title", "Nuevo salón | " . env("APP_NAME"))

@section("content")

    <h1>Nuevo salón</h1>

    <form method="post" action="{{ route('salones.store') }}">
        @csrf
        
        @include('salones._form', ['btnText' => 'Guardar'])

        
    </form>
@endsection