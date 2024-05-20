@extends('plantillas.publico')

@section('nameSection', 'Paso 4: Gestiona el pago de tu curso de extensión')

@section('description')
    Para confirmar su inscripción debe cargar el comprobante de pago. <br><br>
    Haga clic en el botón <strong>"Realizar pago en línea"</strong>, sigas los pasos y cargue su comprobante. <br><br>

    Si usted es beneficiario de un convenios de pagos extemporáneos, solo tendrá que hacer clic en el botón <strong>"Confirmar"</strong>.

@endsection

@section('content')

<div class="content content-full">

  <div class="my-5">

    <form method="post" action="{{ route('public.confirmar-inscripcion') }}" enctype="multipart/form-data">
      @csrf

      <input type="hidden" name="participanteId" value="{{ $participante->getId() }}">
      <input type="hidden" name="total_a_pagar" value="{{ $totalPago }}">
      <input type="hidden" name="valor_descuento" value="{{ $descuento }}">
      <input type="hidden" name="convenioId" value="{{ $convenio->getId() }}">
      <input type="hidden" name="grupoId" value="{{ $grupo->getId() }}">
      <input type="hidden" name="costo_curso" value="{{ $grupo->getCosto() }}">

      @include($formularioAMostrar)

      </form>



  </div>

</div>

<script>
function confirmEcollect() {
    
    Swal.fire({
        title: 'Importante',
        html: 'A partir de ahora serás redirigido a la plataforma de pago Ecollect en una nueva pestaña. Allí podrás completar el proceso de pago de manera segura y confiable.',
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
@endsection