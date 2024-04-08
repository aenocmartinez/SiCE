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
                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>
@endsection