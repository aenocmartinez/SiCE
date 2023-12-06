@if ($total > 0)    
    <ul class="nav-items mb-0">
        <li>
            <a class="text-dark d-flex py-2" href="javascript:void(0)">
                <div class="flex-shrink-0 me-2 ms-3">
                <i class="fa fa-fw fa-user-plus text-success"></i>
                </div>
                <div class="flex-grow-1 pe-2">
                    @if ($total == 1)                        
                        <div class="fw-semibold">{{ $total }} nueva inscripción</div>
                    @else 
                        <div class="fw-semibold">{{ $total }} nuevas inscripciones</div>
                    @endif
                <span class="fw-medium text-muted">Hace {{ $diferenciaMinutos }} min</span>
                </div>
            </a>
        </li>
    </ul>
@endif
