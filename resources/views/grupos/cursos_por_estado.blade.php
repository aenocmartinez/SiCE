@extends("plantillas.principal")

@section("title", $title)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('grupos.index') }}">
        Grupos
    </a>
@endsection

@section("subseccion", $title)

@section("content")
    <table class="table table-vcenter table-hover bordered">
        <thead>
            <tr class="text-center">
                <th style="width: 27%;">Curso</th>
                <th style="width: 5%;">Grupo</th>
                <th style="width: 16%;">Horario</th>
                <th style="width: 16%;">Instructor</th>
                <th style="width: 16%;">#Inscritos / Total de cupos</th>
                <th style="width: 20%;"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cursos as $item)                        
            <tr class="fs-sm">
                <td>{{ $item['curso'] }}</td>
                <td class="text-center">{{ $item['grupo'] }}</td>
                <td class="text-center">
                    {{ $item['dia'] }} / {{ $item['jornada'] }}
                </td>
                <td>{{ $item['orientador'] }}</td>
                <td class="text-center">
                        <h5>
                            {{ $item['total_inscritos'] . " / " . $item['cupos'] }}
                            @if ($item['total_participantes_pendientes_de_pago_sin_convenio'] > 0)
                                <small class="text-danger fs-xs" style="display: block;">
                                    {{ $item['total_participantes_pendientes_de_pago_sin_convenio'] . " participantes sin confirmar el pago" }}
                                    
                                    <a href="{{ route('grupos.participantesPendientesPago', $item['grupo_id']) }}" style="display: block;" class="mt-2">ver participantes</a>
                                </small>
                            @endif
                        </h5>
                </td>
                <td>
                    <a href="{{ route('grupos.descargar-planilla-asistencia', $item['grupo_id']) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-info">
                        <i class="fa fa-fw fa-download"></i> Planilla asistencia
                    </a>                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection