@extends("plantillas.principal")

@section("title", "Nuevo curso")

@section("seccion", "Cursos")
@section("subseccion", "crear curso")

@section("content")
    <form action="{{ route('cursos.store') }}" method="post">
        @csrf
        @include('cursos._form', ['btnText' => 'Guardar'])
    </form>
@endsection