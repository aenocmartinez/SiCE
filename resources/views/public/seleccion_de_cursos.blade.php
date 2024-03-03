@extends('plantillas.publico')


@section('content')


    @csrf

    @include('public._form_seleccion_grupos')   


@endsection