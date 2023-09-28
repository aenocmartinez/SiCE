@extends("plantillas.principal")

@section("title", "Cursos | " . env("APP_NAME"))

@section("content")
    <h1>Nuevo curso</h1>
    <form action="{{ route('cursos.store') }}" method="post">
        @csrf
        @include('cursos._form', ['btnText' => 'Guardar'])
    </form>
@endsection