@extends('plantillas.publico')

@section('nameSection', 'Paso 4: Gestiona el pago de tu curso de extensión')

@section('description')
    Usted está a punto de comenzar el proceso de inscripción a los <strong>Cursos de Extensión</strong> de la Universidad Colegio Mayor de Cundinamarca.
@endsection

@section('content')

<div class="content content-full">

  <div class="my-5">

    <form method="post" action="{{ route('public.confirmar-inscripcion') }}">
      @csrf

      @include('public._form_confirmar_inscripcion')    

      <div class="my-1 text-center">

          @if ( $participante->vinculadoUnicolMayor() )
            <button class="btn btn-primary px-4 py-2" data-toggle="click-ripple">
              <i class="fa fa-fw fa-database me-1"></i>          
              Confirmar inscripción
            </button>
          @else 
            <button class="btn btn-primary px-4 py-2" data-toggle="click-ripple">          
              <i class="fa fa-fw fa-sack-dollar me-1"></i>
              Realizar pago por Ecollect
            </button>          
          @endif

      </div>
      </form>

  </div>

</div>
@endsection