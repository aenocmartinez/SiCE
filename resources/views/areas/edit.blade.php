@extends("plantillas.principal")

@section("title", "Nueva área | " . env("APP_NAME"))

@section("content")

    <h1>Editar área</h1>

    <form method="post" action="{{route('areas.update')}}">
        @csrf @method('patch')
        <input type="hidden" name="id" value="{{ $area['id'] }}">
        <input type="text" name="nombre" placeholder="Nombre" value="{{ $area['nombre'] }}">
        {!! $errors->first('nombre', '<br><small>:message</small>') !!}
        <br>
        <button>Guardar</button>
    </form>
@endsection