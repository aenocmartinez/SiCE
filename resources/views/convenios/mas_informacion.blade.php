@extends("plantillas.principal")

@php
    $titulo = "Más información del convenio";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('convenios.index') }}">
        Convenios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('convenios.exportar-participantes', $convenio->getId()) }}" class="btn btn-outline-info me-1 mb-3">
            <i class="fa fa-fw fa-download me-1"></i> Descargar participantes
        </a>
        @if ($convenio->esCooperativa() && !$convenio->haSidoFacturado())
        <a href="{{ route('convenios.facturar', $convenio->getId()) }}" id="aplicar_descuento_facturacion" data-parametro="{{ $convenio->getId() }}" class="btn btn-outline-info me-1 mb-3">
            <i class="fa fa-fw fa-circle-dollar-to-slot me-1"></i> Aplicar descuento y obtener datos de factura
        </a>        
        @endif
    </div>
</div>   

<div class="block block-rounded">

    <div class="block-content">
          
          <div class="block block-rounded">
            <div class="block-content">
              <div class="table-responsive">
                <table class="table table-bordered table-vcenter">
                  <tbody>
                        <tr class="fs-sm">
                            <td>Convenio</td>
                            <td>{{ $convenio->getNombre() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Descuento</td>
                            <td>{{ $convenio->getDescuento() }}%</td>
                        </tr>                         
                        <tr class="fs-sm">
                            <td>Número proyectado de beneficiados</td>
                            <td>{{ $convenio->getNumeroBeneficiados() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Total de inscripciones realizadas</td>
                            <td>{{ $convenio->getNumeroInscritos() }}</td>
                        </tr>                          
                        <tr class="fs-sm">
                            <td>Periodo</td>
                            <td>{{ $convenio->getNombreCalendario() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Fechas</td>
                            <td>{{ $convenio->getFecInicio() }} / {{ $convenio->getFecFin() }}</td>
                        </tr>                        
                        <tr class="fs-sm">
                            <td>Total a pagar</td>
                            <td>{{ $convenio->getTotalAPagarFormatoMoneda() }}</td>
                        </tr>                        
                        <tr class="fs-sm">
                            <td>Comentarios</td>
                            <td>{{ $convenio->getComentarios() }}</td>
                        </tr>                           
                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>

<script>
    function aplicarDescuentoObtenerDatosFactura(convenio) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Al confirmar esta acción el sistema aplicará el descuento a los participantes inscritos de este convenio y no se podrá deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, estoy seguro',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = "{{ route('convenios.facturar', ':parametro') }}".replace(':parametro', convenio);
            }
        });        
    }

    document.getElementById('aplicar_descuento_facturacion').addEventListener('click', function(e) {
        e.preventDefault();
        var parametro = this.getAttribute('data-parametro');
        aplicarDescuentoObtenerDatosFactura(parametro);
    });
</script>
@endsection