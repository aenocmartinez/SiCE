@extends("plantillas.principal")

@section("title", "Editar área | " . env("APP_NAME"))

@section("content")

    <form method="post" action="{{route('areas.update')}}">
        @csrf @method('patch')
        
        <input type="hidden" name="id" value="{{ $area['id'] }}">
        
        @include('areas._form', ['btnText' => 'Actualizar'])

    </form>
    
@endsection