@extends("plantillas.principal")

@section("title", "Orientadores | " . env("APP_NAME"))

@section("content")
<h1>Orientadores</h1>

@php
$criterio = isset($criterio) ? $criterio : '';
@endphp

<form method="post" action="{{ route('orientadores.buscador') }}">
    @csrf
    <label>Buscador</label>
    <input type="text" name="criterio" value="{{ $criterio }}" placeholder="Nombre, capacidad">

    <button>Buscar</button>
</form>
<br>


<a href="{{ route('orientadores.create') }}">+ Crear orientador</a>

<ul>
    @forelse ($orientadores as $o)
    <li>
        {{ $o['nombre'] }} 
        <small>( {{ $o['tipo_documento'] .". " . $o['documento'] . " / " . ($o['estado'] ? 'Activo':'Inactivo')}} )</small>
        <form method="post" action="{{ route('orientadores.delete', ['id' => $o['id']]) }}">
                @csrf @method('delete')
                <button>Eliminar</button>
            </form>
        <a href="{{ route('orientadores.edit', $o['id']) }}">Editar</a>
    </li>
    @empty
        <li>No hay orientadores para mostrar</li>
    @endforelse    
</ul>
@endsection