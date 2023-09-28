@extends("plantillas.principal")

@section("title", "Salones | " . env("APP_NAME"))

@section("content")
<h1>Salones</h1>

<ul>
    @forelse ($salones as $salon)
        <li>{{ $salon['nombre'] }}</li>
    @empty
        <li>No hay salones para mostrar</li>
    @endforelse
</ul>
@endsection