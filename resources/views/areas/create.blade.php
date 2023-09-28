@extends("plantillas.principal")

@section("title", "Nueva área | " . env("APP_NAME"))

@section("content")

    <h1>Nueva área</h1>

    <form method="post" action="{{ route('areas.store') }}">
        @csrf
        <input type="text" name="nombre" placeholder="Nombre" value="{{ old('nombre') }}">
        {!! $errors->first('nombre', '<br><small>:message</small>') !!}
        <br>
        <button>Guardar</button>
    </form>
@endsection