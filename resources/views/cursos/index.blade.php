@extends("plantillas.principal")

@section("title", "Cursos")
@section("description", "Listado de cursos")

@section("content")
    <a href="{{ route('cursos.create') }}">+ Crear curso</a>

    <ul>
        @forelse ($cursos as $curso)
        <li>
            {{ $curso['nombre'] }} <small>({{ $curso['area']['nombre'] . " / " . $curso['modalidad'] }})</small> 
            <form method="post" action="{{ route('cursos.delete', ['id' => $curso['id']]) }}">
                @csrf @method('delete')
                <button>Eliminar</button>
            </form> 
            <a href="{{ route('cursos.edit', $curso['id']) }}">Editar</a>
        </li>
        @empty
            <li>No hay cursos para mostrar</li>
        @endforelse
    </ul>
@endsection