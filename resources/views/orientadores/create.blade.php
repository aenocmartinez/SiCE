@extends("plantillas.principal")

@section("title", "Nuevo orientador")

@section("seccion", "Orientadores")
@section("subseccion", "nuevo orientador")

@section("content")

    <form method="post" action="{{ route('orientadores.store') }}">
        @csrf
        
        @include('orientadores._form', ['btnText' => 'Guardar'])

    </form>
@endsection