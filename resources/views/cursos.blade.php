@extends("plantillas.principal")

@section("title", "Cursos | " . env("APP_NAME"))

@section("content")
    <h1>Cursos</h1>

    <ul>
        @forelse ($cursos as $curso)
        <li>{{ $curso['nombre'] }}</li>
        @empty
            <li>No hay cursos para mostrar</li>
        @endforelse
    </ul>
@endsection