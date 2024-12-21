@extends("plantillas.principal")

@section("title", "Participantes pendientes de pago")

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('dashboard') }}">
        dashboard
    </a>
@endsection

@section("subseccion")

@section("content")
    <table class="table table-vcenter table-hover bordered">
        <thead>
            <tr class="text-center">
                <th style="width: 5%;"></th>
                <th>Participante</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th style="width: 15%;">Fecha Inscripción</th>
                <th style="width: 15%;">Fecha Max. Legalización</th>
                <th>Número formulario</th>
            </tr>
        </thead>
        @foreach ($participantes as $participante)
            <tr class="fs-xs text-center">
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    {{ $participante->getNombreCompleto() }}
                    <br>
                    {{ $participante->getDocumentoCompleto() }}

                </td>
                <td>{{ $participante->getEmail() }}</td>
                <td>{{ $participante->getTelefono() }}</td>
                <td>{{ $participante->getFormularioInscripcion()->getFechaCreacion() }}</td>
                <td>{{ $participante->getFormularioInscripcion()->getFechaMaxLegalizacion() }}</td>
                <td>{{ $participante->getFormularioInscripcion()->getNumero() }}</td>
            </tr>            
        @endforeach
        <tbody>

        </tbody>
    </table>
@endsection