@extends('plantillas.publico')

@section('nameSection', 'Paso 4: Gestiona el pago de tu curso de extensión')

@section('description')
    Usted está a punto de comenzar el proceso de inscripción a los <strong>Cursos de Extensión</strong> de la Universidad Colegio Mayor de Cundinamarca.
@endsection

@section('content')

<div class="content content-full">

  <div class="my-5">

    <form method="post" action="{{ route('public.confirmar-inscripcion') }}" enctype="multipart/form-data">
      @csrf

      @include('public._form_confirmar_inscripcion')    

      <div class="my-1 text-end">

          @if ( $participante->vinculadoUnicolMayor() )
            <button class="btn btn-primary px-4 py-2" data-toggle="click-ripple">
              <i class="fa fa-database me-1"></i>          
              Confirmar inscripción
            </button>
          @else 

            
          @endif

      </div>
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