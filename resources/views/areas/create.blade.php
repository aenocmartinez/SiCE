@extends("plantillas.principal")

@section("title", "Nueva área")
@section("description", "Ingrese todos los datos")

@section("content")
    <form method="post" action="{{ route('areas.store') }}">
        @csrf
        
        @include('areas._form', ['btnText' => 'Guardar'])

        
    </form>
@endsection