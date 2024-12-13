@extends("plantillas.principal")

@section("title", "Usuarios del sistema")
@section("description")


@section("content")

<div class="row mb-3">
    <div class="d-flex justify-content-end">
        <a href="{{ route('users.create') }}" class="btn btn-lg btn-info">
            <i class="fa fa-circle-plus me-1 opacity-50"></i> Agregar nuevo usuario
        </a>
    </div>
</div>    

<div class="row">
    <div class="block block-rounded">
        <div class="block-content">
            <table class="table table-vcenter">
                <thead>
                    <tr class="text-center">
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Creado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="fs-xs">
                    @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->getNombre() }}</td>
                        <td>{{ $usuario->getRole() }}</td>
                        <td>{{ $usuario->getEmail() }}</td>
                        <td class="text-center">
                            <span class="badge {{ $usuario->getEstado() == 'Activo' ? 'bg-success' : 'bg-danger' }} bg-success fs-xs text-white">{{ $usuario->getEstado() }}</span>
                        </td>
                        <td>{{ $usuario->getFechaCreacion() }}</td>
                        <td>
                        <div class="d-sm-table-cell">
                            <a href="{{ route('users.edit', $usuario->getId()) }}" class="fs-xs fw-semibold d-inline-block py-1 px-3 btn rounded-pill btn-outline-secondary">
                                <i class="fa fa-fw fa-pencil-alt"></i> Editar
                            </a>                                                
                        </div>                            
                        </td>
                    </tr>                        
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron registros.</td>
                    </tr>                        
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>

@endsection