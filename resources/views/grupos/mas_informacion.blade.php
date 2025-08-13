@extends("plantillas.principal")

@php
    $titulo = "Más información del grupo";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('grupos.index') }}">
        Grupos
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
                            <td>Código</td>
                            <td>{{ $grupo->getNombre() }}</td>
                        </tr>                    
                        <tr class="fs-sm">
                            <td>Curso</td>
                            <td>{{ $grupo->getNombreCurso() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Instructor</td>
                            <td>{{ $grupo->getNombreOrientador() }}</td>
                        </tr>                        
                        <tr class="fs-sm">
                            <td>Número de participantes</td>
                            <td>{{ $grupo->getTotalInscritos() }}</td>
                        </tr>                         
                        <tr class="fs-sm">
                            <td>Periodo</td>
                            <td>{{ $grupo->getNombreCalendario() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Horario</td>
                            <td>{{ $grupo->getDia() }} / {{ $grupo->getJornada() }}</td>
                        </tr>
                        <tr class="fs-sm">
                            <td>Salón</td>
                            <td>{{ $grupo->getNombreSalon() }}</td>
                        </tr>  
                        <tr class="fs-sm">
                            <td>Total de cupo</td>
                            <td>{{ $grupo->getCupo() }}</td>
                        </tr>                                                                                                                       
                  </tbody>
                </table>
              </div>
            </div>
          </div>          

</div>
@endsection