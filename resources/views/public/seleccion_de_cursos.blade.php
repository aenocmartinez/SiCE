@extends('plantillas.publico')

@section('nameSection', 'Paso 3: Selecciona el curso en el que quieres participar')

@section('description')
    Usted está a punto de comenzar el proceso de inscripción a los <strong>Cursos de Extensión</strong> de la Universidad Colegio Mayor de Cundinamarca.
@endsection

@section('content')

    @include('public._form_seleccion_grupos')   


@endsection