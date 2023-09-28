@extends("plantillas.principal")

@section("title", "Áreas | " . env("APP_NAME"))

@section("content")
    <h1>Listado de áreas</h1>
    
    <a href="{{ route('areas.create') }}">+ Crear área</a>

    <ul>
        @forelse ($areas as $area)
            <li>{{ $area['nombre'] }}
                <form method="post" action="{{ route('areas.delete', ['id' => $area['id']]) }}">
                    @csrf @method('delete')
                    <button>Eliminar</button>
                </form>
                <a href="{{ route('areas.edit', $area['id']) }}">Editar</a>
            </li>
        @empty
            <li>No hay áreas para mostrar</li>
        @endforelse             
    </ul>    
@endsection