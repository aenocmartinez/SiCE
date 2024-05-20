@extends('plantillas.publico')

@section('nameSection', 'Paso 3: Selecciona el curso en el que quieres participar')

@section('description')
    Es el momento de seleccionar el curso al que desea inscribirse. <br>
    Haga clic en el área, se le mostrarán los cursos disponbiles, elija el de su interés y haga clic en el botón "Inscribirse".
@endsection

@section('content')

    @include('public._form_seleccion_grupos')   


@endsection