@extends('plantillas.publico')

@section('nameSection', 'Tienes Pagos pendientes')

@section('description')
    Identificamos que tienes pagos pendientes por hacer. <br>
    Puedes elegir si quieres hacer una nueva inscripción o si formalizas el pago.    
@endsection

@section('content')

    @include('public._form_pagos_pendientes')   

@endsection