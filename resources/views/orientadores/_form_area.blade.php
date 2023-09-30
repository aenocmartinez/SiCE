<!-- {{ $orientador['nombre'] }} -->

<div class="block block-rounded">
    <div class="block-content">
        <div class="row push">

            <h5 class="fw-light link-fx mb-4 text-primary-darker">
               {{ mb_strtoupper($orientador['nombre']) }}
            </h5>

            <form method="POST" action="{{ route('orientadores.addArea') }}" id="fmAddArea">
                <div class="row">
                        <div class="col-4">
                            
                                @csrf
                                <input type="hidden" name="idOrientador" value="{{ $orientador['id'] }}">                
                                <select class="form-select @error('area') is-invalid @enderror" id="area" name="area">
                                <option value="">Selecciona un área</option>
                                    @foreach ($areas as $area)            
                                        <option value="{{ $area['id'] }}">{{ $area['nombre'] }}</option>
                                    @endforeach
                                </select>
                                @error('area')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            
                        </div>
                        <div class="col-4">
                            <button class="btn btn-info">Agregar área</button> 
                        </div>               
                </div>
            </form>

            <div class="col-12 mt-3">
                <table class="table table-vcenter">
                @forelse ($orientador['areas'] as $a)
                @php
                    $idForm = $orientador['id'] . $a->getId();
                @endphp                
                    <tr>
                        <td class="fs-sm" style="width: 95%;">
                            <h5 class="fw-light mb-0">{{ $a->getNombre() }}</h5>
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('orientadores.removeArea', [$orientador['id'], $a->getId()]) }}" id="fm_{{$idForm}}">
                                @csrf @method('delete')                                
                                <button class="btn btn-sm btn-alt-secondary" 
                                        data-bs-toggle="tooltip" 
                                        title="Quitar área" 
                                        type="button"
                                        data-id="{{$idForm}}"
                                        onclick="confirmDelete(this)">
                                    <i class="fa fa-fw fa-trash-can"></i>
                                </button>
                            </form>
                        </td>                    
                    </tr>
                    @empty
                    <tr>
                        <td class="text-center">El orientador no tiene áreas asociadas para mostrar</td>
                    </tr>
                    @endforelse 
                </table>                
            </div>

        </div>
    </div>
</div>

<script>
function confirmDelete(button) {
    const orientadorAreaId = button.getAttribute('data-id'); 
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, estoy seguro',
        cancelButtonText: 'Cancelar'
    }).then((result) => {        
        if (result.isConfirmed) {
            const form = document.getElementById(`fm_${orientadorAreaId}`);
            if (form) {                  
                form.submit();
            }
        }
    });
}
</script>