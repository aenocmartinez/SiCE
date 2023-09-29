@extends("plantillas.principal")

@section("title", "Salones | " . env("APP_NAME"))

@section("content")
<h1>Salones</h1>


@php
$criterio = isset($criterio) ? $criterio : '';
@endphp

<form method="post" action="{{ route('salones.buscador') }}">
    @csrf
    <label>Buscador</label>
    <input type="text" name="criterio" value="{{ $criterio }}" placeholder="Nombre, capacidad">

    <button>Buscar</button>
</form>
<br>

<a href="{{ route('salones.create') }}">+ Crear salón</a>
<ul>
    @forelse ($salones as $salon)
        <li>
            {{ $salon['nombre'] }}<br>
            <small>
                Capacidad: {{ $salon['capacidad'] }}<br>
                Estado: {{ $salon['esta_disponible'] ? 'disponible' : 'no disponible' }}
            </small>
            <form method="post" action="{{ route('salones.delete', ['id' => $salon['id']]) }}">
                @csrf @method('delete')
                <button>Eliminar</button>
            </form>
            <a href="{{ route('salones.edit', $salon['id']) }}">Editar</a>
        </li>
    @empty
        <li>No hay salones para mostrar</li>
    @endforelse
</ul>
@endsection