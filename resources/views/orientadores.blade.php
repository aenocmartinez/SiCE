@extends("plantillas.principal")

@section("title", "Orientadores | " . env("APP_NAME"))

@section("content")
<h1>Orientadores</h1>

<ul>
    @forelse ($orientadores as $orientador)
    <li>{{ $orientador['nombre'] }}</li>
    @empty
        <li>No hay orientadores para mostrar</li>
    @endforelse    
</ul>
@endsection