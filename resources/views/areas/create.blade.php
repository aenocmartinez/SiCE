@extends("plantillas.principal")

@section("title", "Nueva área | " . env("APP_NAME"))

@section("content")

    <h1>Nueva área</h1>

    <form method="post" action="{{ route('areas.store') }}">
        @csrf
        
        @include('areas._form', ['btnText' => 'Guardar'])

        
    </form>
@endsection