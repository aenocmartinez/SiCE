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
                            <td>Participantes beneficiados</td>
                            <td>{{ $convenio->getNumeroBeneficiados() }}</td>
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