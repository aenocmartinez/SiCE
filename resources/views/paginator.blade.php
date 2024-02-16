<div class="block-content mt-0">
    <nav aria-label="Page navigation">
      <div class="pagination pagination-sm justify-content-center">

        @if (!$paginate->IsFirst())        
          <a class="page-link" href="{{ route($route, $paginate->Previous()) }}" aria-label="Previous">
            <span aria-hidden="true">
              <i class="fa fa-angle-left mb-2 mt-1 p-1 text-muted"></i>
            </span>
          </a>
        @endif

        <span class="text-muted fs-sm mb-2 mt-1 p-1">Página</span>
        <div class="btn-toolbar mb-1" role="toolbar" aria-label="Paginador">
          <select class="form-select fs-sm text-muted" id="page" name="page" onchange="paginate()">
            @for($i=1; $i <= $paginate->NumberOfPages(); $i++)
              <option value="{{ route($route, $i) }}" {{ ($i == $paginate->Page() ? 'selected' : '') }}>{{ $i }}</option>        
            @endfor
          </select>          
        </div>
        <span class="text-muted fs-sm mb-2 mt-1 p-1">de {{ $paginate->NumberOfPages() }} página(s)</span>

        @if (!$paginate->IsLast())          
          <a class="page-link" href="{{ route($route, $paginate->Next()) }}" aria-label="Next">
            <span aria-hidden="true">
              <i class="fa fa-angle-right mb-2 mt-1 p-1 text-muted"></i>
            </span>
          </a>        
        @endif
        <!-- <div class="pagination pagination-sm justify-content-end text-muted fs-sm mb-2 mt-1 p-1"> {{ $paginate->TotalRecords() }} registros</div>         -->
      </div> 
      
    </nav>  
</div>

<script>
  function paginate() {
    var select = document.getElementById("page");
    var opcionSeleccionada = select.options[select.selectedIndex].value;
    window.location.href = opcionSeleccionada;
  }
</script>