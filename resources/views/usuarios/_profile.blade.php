
@php
    $checked = ($usuario->getEstado() == 'Activo' ? 'checked' : '');    
@endphp


<div class="block block-rounded">
    <div class="block-content">

        <div class="row push">
            <div class="col-lg-8 col-xl-5">

            <div class="mb-4">
                <!-- <label class="form-label" for="nombre">Nombre</label> -->
                <input type="text" 
                    class="form-control @error('nombre') is-invalid @enderror" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Nombre" 
                    value="{{ old('nombre', $usuario->getNombre()) }}"                
                    >
                    @error('nombre')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                <br>   
                
                <!-- <label class="form-label" for="email">Correo electrónico</label> -->
                <input type="text" 
                    class="form-control @error('email') is-invalid @enderror" 
                    id="email" 
                    name="email" 
                    placeholder="Correo electrónico" 
                    value="{{ old('email', $usuario->getEmail()) }}"                
                    >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                <br>   
                
                <!-- <label class="form-label" for="password">Password</label> -->
                <input type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    id="password" 
                    name="password" 
                    placeholder="Contraseña" 
                    value="{{ old('password') }}"                
                    >
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                    @enderror

                    <br> 

                <br>                   

                <button class="btn btn-large btn-info">{{ $btnText }}</button>        
                <a href="{{ route('users.index') }}" class="btn btn-large btn-light"> Cancelar</a>

            </div>
        </div>

    </div>
</div>