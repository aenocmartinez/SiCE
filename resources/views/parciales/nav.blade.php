<nav>
    <ul>
        <li class="{{ setActive('home') }}"><a href=" {{ route('home') }}">Inicio</a></li>
        <li class="{{ setActive('areas.index') }}"><a href="{{ route('areas.index') }}">Áreas</a></li>
        <li class="{{ setActive('cursos.index') }}"><a class="active" href="{{ route('cursos.index') }}">Cursos</a></li>
        <li class="{{ setActive('salones.index') }}"><a href="{{ route('salones.index') }}">Salones</a></li>
        <li class="{{ setActive('orientadores.index') }}"><a href="{{ route('orientadores.index') }}">Orientadores</a></li>
    </ul>
</nav>