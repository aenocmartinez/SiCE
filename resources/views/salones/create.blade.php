@extends("plantillas.principal")

@section("title", "Nuevo salón")

@section("seccion", "Salones")
@section("subseccion", "nuevo salón")

@section("content")

    <form method="post" action="{{ route('salones.store') }}">
        @csrf        
        @include('salones._form', ['btnText' => 'Guardar'])
    </form>
@endsection