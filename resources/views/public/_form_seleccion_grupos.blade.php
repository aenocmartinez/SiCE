@php
    $flag = true;
@endphp

<div class="bg-body-light mb-1">
  <div class="content content-full">
    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
      <div class="flex-grow-1">
        <h1 class="h3 fw-normal mb-1">
          {{ $participante->getNombreCompleto() }}
        </h1>
        <h2 class="fs-base lh-base fw-normal text-dark mb-0">
          {{ $participante->getDocumentoCompleto() }} <br>
          <span class="fs-sm text-dark">{{ \Carbon\Carbon::now('America/Bogota')->locale('es_CO')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
        </h2>
      </div>

    </div>
  </div>
</div>

@php
    $total_a_pagar = 0;
    $numero_cursos = 0;
    if (session()->has('cursos_a_matricular')) {
        $numero_cursos = count(Session::get('cursos_a_matricular'));
    }        
@endphp

<!-- Carrito de compra -->
<div class="block-header">
    <h3 class="block-title text-end">
    <i class="fa fa-fw fa-shopping-cart text-muted me-1"></i> Cursos seleccionados ({{ $numero_cursos }})
    </h3>
</div>
@if ($numero_cursos > 0)  
<div class="block-content">
  <table class="table table-bordered table-vcenter fs-sm">
    <thead>
      <tr class="text-center">
        <th></th>
        <th>Curso</th>
        <th>Costo</th>
        <th>Descuento</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach (Session::get('cursos_a_matricular') as $curso)        
      <tr>
        <td class="text-center">
          <a class="text-muted fs-sm" href="{{ route('public.quitar-curso', [$participante->getId(), $curso['grupoId'], $formularioId]) }}">
            <i class="fa fa-trash-can"></i>
          </a>
        </td>
        <td>
          <a class=" text-info" href="#">
            {{ 'G'.$curso['grupoId'] }} - {{ $curso['nombre_curso'] }}
          </a>
          <div class="text-muted">{{ $curso['dia'] . " / " . $curso['jornada'] }}</div>
        </td>
        <td class="text-center fs-sm"> {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($curso['costo_curso']) }} </td>
        <td class="text-center fs-sm"> {{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($curso['descuento']) }} </td>
        <td class="text-end">
          <div class="fw-semibold fs-sm">{{ $curso['totalPagoFormateado'] }}</div>
        </td>
      </tr>
      @php
        $total_a_pagar += $curso['totalPago']; 
      @endphp
      @endforeach
      <tr>
        <td class="text-end fw-bold h6" colspan="4">Total a pagar</td>
        <td class="text-end">
          <div class="fw-semibold text-end">{{ Src\infraestructure\util\FormatoMoneda::PesosColombianos($total_a_pagar) }}</div>
        </td>
      </tr>  
      <tr>
        <td class="text-end" colspan="5">
          <a href="{{ route('public.pagar-matricula', [$participante->getId()]) }}" 
                    class="btn fw-medium fw-semibold d-inline-block py-1 px-3 rounded-pill bg-success-light text-success"
                    data-bs-toggle="tooltip" 
                    data-toggle="click-ripple"
                    title="Ir a pagar">
                    Ir a pagar
            </a>
        </td>
      </tr>            
    </tbody>
  </table>  
</div>
@endif

<!-- Fin carrito de compra -->

<div class="block block-rounded row g-0">
    <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-4 col-xxl-2" role="tablist">
        @foreach ($items as $index => $item)        
        <li class="nav-item d-md-flex flex-md-column">
        <button class="nav-link text-md-start {{ $flag ? 'active' : ''}}" id="tab-{{ $item->areaId }}" data-bs-toggle="tab" data-bs-target="#btabs-{{ $item->areaId }}" role="tab" aria-controls="btabs-{{ $item->areaId }}" aria-selected="{{ $flag ? 'true' : ''}}">
            <!-- <i class="fa fa-fw fa-home opacity-50 me-1 d-none d-sm-inline-block"></i> -->
            <span>{{ $item->areaNombre }}</span>
        </button>
        </li>
        @php
            $flag = false;
        @endphp        
        @endforeach
    </ul>
    <div class="tab-content col-md-8 col-xxl-10">
    @foreach ($items as $index => $item)
        <div class="block-content tab-pane {{ !$flag ? 'active' : ''}}" id="btabs-{{ $item->areaId }}" role="tabpanel" aria-labelledby="tab-{{ $item->areaId }}" tabindex="0">
        <table class="table table-bordered table-vcenter">
                    <thead>
                      <tr class="text-center">
                        <th>Curso</th>
                        <th>Horario</th>
                        <th>Costo</th>
                        <th>Cup. disp.</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($item->grupos as $grupo)
                      <tr class="fs-sm">
                        <td class="text-info">
                          {{ $grupo->grupoNombre . ": " . $grupo->cursoNombre }} <br>
                          <span style="font-size: 12px;" class="text-dark">Orientador: {{ $grupo->nombreOrientador }}</span>
                        </td>
                        <td class="text-center">{{ $grupo->dia . " / " . $grupo->jornada }}</td>
                        <td class="text-center">{{ $grupo->costo }}</td>                        
                        <td class="text-center">{{ $grupo->cuposDisponibles }}</td>
                        <td class="d-none d-sm-table-cell text-center">
                        @if ($grupo->cuposDisponibles > 0)                          
                          <a href="{{ route('public.agregar_curso_a_matricula', [$participante->getId(), $grupo->grupoId, $formularioId]) }}" 
                                  class="btn fs-xs fw-semibold d-inline-block py-1 px-3 rounded-pill bg-info-light text-info"
                                  data-bs-toggle="tooltip" 
                                  data-toggle="click-ripple"
                                  title="inscribirse">
                                  Agregar curso
                          </a>  
                        @endif
                        </td>
                      </tr>
                      @endforeach 
                    </tbody>
                  </table>            
        </div>        
        @php
            $flag = true;
        @endphp    
    @endforeach
    </div>
</div>