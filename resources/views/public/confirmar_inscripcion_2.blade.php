@extends('plantillas.publico')

@section('nameSection', 'Paso 4: Gestiona el pago de tu curso de extensión')

@section('description')
    Para confirmar su inscripción debe cargar el comprobante de pago. <br><br>
    Haga clic en el botón <strong>"Realizar pago en línea"</strong>, sigas los pasos y cargue su comprobante. <br><br>

    Si usted es beneficiario de un convenios de pagos extemporáneos, solo tendrá que hacer clic en el botón <strong>"Confirmar"</strong>.

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

  <div class="content content-full">

    <div>

      <form method="post" action="{{ route('public.confirmar-inscripcion2') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="participanteId" value="{{ $participante->getId() }}">
        <input type="hidden" name="formularioId" value="{{ $formularioId }}">

        @include($formularioAMostrar)

        </form>

    </div>

  </div>

  <script>
  function confirmEcollect() {
      
      Swal.fire({
          title: 'Importante',
          html: 'A partir de ahora serás redirigido a la plataforma de pago Ecollect en una nueva pestaña. Recuerda subir el comprobante de pago en formato PDF para formalizar la inscripción una vez completes el pago.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, estoy seguro',
          cancelButtonText: 'Cancelar'
      }).then((result) => {
          if (result.isConfirmed) {
            var miRedirect = document.createElement('a');
            miRedirect.setAttribute('href', 'https://www.e-collect.com/customers/pagosunicolmayor.htm');
            miRedirect.setAttribute('target', '_blank');
            miRedirect.click();          
          }
      });
  }
  </script>

@else
    Su sesión ha finalizado
@endif  

@endsection