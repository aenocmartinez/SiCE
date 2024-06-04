@extends('plantillas.publico')

@section('nameSection', 'Tienes Pagos pendientes')

@section('description')
    Identificamos que tienes pagos pendientes por hacer. <br>
    Puedes elegir si quieres hacer una nueva inscripción o si formalizas el pago.    
@endsection

@section('content')

@if (session()->has('SESSION_UUID'))

    @section('header')
        <header id="page-header">
            <div class="content-header">

            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-fw fa-bars"></i>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="dropdown d-inline-block ms-2">
                <a href="{{ route('public.salidaSegura') }}" type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center">
                <img class="rounded-circle" src="{{asset('assets/media/avatars/avatar10.jpg')}}" alt="Header Avatar" style="width: 21px;">
                
                <span class="d-none d-sm-inline-block ms-2">Salida segura</span>
                <i class="fa fa-fw fa-arrow-right-to-bracket d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
                </a>
                </div>
            </div>

            </div>
        </header>
    @endsection

    @include('public._form_pagos_pendientes')   

@else
    Su sesión ha finalizado
@endif

@endsection