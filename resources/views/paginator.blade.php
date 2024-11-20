@php
    // Inicializar los datos de navegación
    $dataPrevious = ['page' => $paginate->Previous()];
    $dataNext = ['page' => $paginate->Next()];

    // Adaptar criterios según la ruta actual
    if (isset($route)) {
        if (Str::contains($route, ['grupos'])) {
            // Para 'grupos.buscador-paginador'
            $dataPrevious = ['criterio' => $criterio, 'page' => $paginate->Previous()];
            $dataNext = ['criterio' => $criterio, 'page' => $paginate->Next()];
        } elseif (Str::contains($route, ['cursos', 'salon', 'orientadores', 'salones', 'participantes'])) {
            // Para 'cursos.buscador-paginador', 'salon', 'orientadores', 'participantes'
            $dataPrevious = ['criteria' => $criterio, 'page' => $paginate->Previous()];
            $dataNext = ['criteria' => $criterio, 'page' => $paginate->Next()];
        }

        // Añadir 'periodo' si está definido
        if (isset($periodo)) {
            $dataPrevious['periodo'] = $periodo->getId();
            $dataNext['periodo'] = $periodo->getId();
        }
    }
@endphp

<div class="block-content mt-0">
    <nav aria-label="Page navigation">
        <div class="pagination pagination-sm justify-content-center">

            @if (!$paginate->IsFirst())
                <a class="page-link"
                   href="{{ route($route, $dataPrevious) }}"
                   aria-label="Previous">
                   <span aria-hidden="true">
                       <i class="fa fa-angle-left mb-2 mt-1 p-1 text-muted"></i>
                   </span>
                </a>
            @endif

            <span class="text-muted fs-sm mb-2 mt-1 p-1">Página</span>
            <div class="btn-toolbar mb-1" role="toolbar" aria-label="Paginador">
                <select class="form-select fs-sm text-muted" id="page" name="page" onchange="paginate()">
                    @for ($i = 1; $i <= $paginate->NumberOfPages(); $i++)
                        @php
                            // Generar parámetros dinámicamente para cada página
                            $data = ['page' => $i];

                            if (Str::contains($route, ['grupos'])) {
                                $data['criterio'] = $criterio;
                            } elseif (Str::contains($route, ['cursos', 'salon', 'orientadores', 'salones', 'participantes'])) {
                                $data['criteria'] = $criterio;
                            }

                            if (isset($periodo)) {
                                $data['periodo'] = $periodo->getId();
                            }
                        @endphp

                        <option value="{{ route($route, $data) }}" {{ $i == $paginate->Page() ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
            <span class="text-muted fs-sm mb-2 mt-1 p-1">de {{ $paginate->NumberOfPages() }} página(s)</span>

            @if (!$paginate->IsLast())
                <a class="page-link"
                   href="{{ route($route, $dataNext) }}"
                   aria-label="Next">
                   <span aria-hidden="true">
                       <i class="fa fa-angle-right mb-2 mt-1 p-1 text-muted"></i>
                   </span>
                </a>
            @endif

        </div>
    </nav>
    <div class="pagination pagination-sm justify-content-center text-muted fs-sm mb-2 mt-1 p-1">
        {{ $paginate->TotalRecords() }} registros en total
    </div>
</div>

<script>
    function paginate() {
        var select = document.getElementById("page");
        var opcionSeleccionada = select.options[select.selectedIndex].value;
        window.location.href = opcionSeleccionada;
    }
</script>
