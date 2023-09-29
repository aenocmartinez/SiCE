<nav class="navbar navbar-light navbar-expand-lg bg-white shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="{{ route('home') }}">
            {{ config('app.name') }}
        </a>
    
        <button class="navbar-toggler" type="button"
                data-toggle="collapse"
                data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="{{ __('Toggle navigation') }}"
                ><span class="navbar-toggler-icon"></span>
    
        </button>
    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link {{ setActive('home') }}" href=" {{ route('home') }}">Inicio</a></li>
                <li class="nav-item"><a class="nav-link {{ setActive('areas.index') }}" href="{{ route('areas.index') }}">Áreas</a></li>
                <li class="nav-item"><a class="nav-link {{ setActive('cursos.index') }}" class="active" href="{{ route('cursos.index') }}">Cursos</a></li>
                <li class="nav-item"><a class="nav-link {{ setActive('salones.index') }}" href="{{ route('salones.index') }}">Salones</a></li>
                <li class="nav-item"><a class="nav-link {{ setActive('orientadores.index') }}" href="{{ route('orientadores.index') }}">Orientadores</a></li>
            </ul>
        </div>
    </div>
</nav>

@include('parciales.session-status')