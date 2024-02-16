@if ($paginate->TotalRecords() > 0) 

  <div class="block-content mt-0">
      <nav aria-label="Page navigation">
        <div class="pagination pagination-sm justify-content-center">

          @if (!$paginate->IsFirst())   
              @if (strlen($criterio)>0)
              <a class="page-link" href="{{ route($route, [$paginate->Previous(), $criterio]) }}" aria-label="Previous">
                <span aria-hidden="true">
                  <i class="fa fa-angle-left mb-2 mt-1 p-1 text-muted"></i>
                </span>
              </a>              
              @else
              <a class="page-link" href="{{ route($route, $paginate->Previous()) }}" aria-label="Previous">
                <span aria-hidden="true">
                  <i class="fa fa-angle-left mb-2 mt-1 p-1 text-muted"></i>
                </span>
              </a>  
              @endif 
          @endif

          <span class="text-muted fs-sm mb-2 mt-1 p-1">Página</span>
          <div class="btn-toolbar mb-1" role="toolbar" aria-label="Paginador">
            <select class="form-select fs-sm text-muted" id="page" name="page" onchange="paginate()">
              @for($i=1; $i <= $paginate->NumberOfPages(); $i++)
                @if (strlen($criterio)>0)
                  <option value="{{ route($route, [$i, $criterio]) }}" {{ ($i == $paginate->Page() ? 'selected' : '') }}>{{ $i }}</option>
                @else
                  <option value="{{ route($route, $i) }}" {{ ($i == $paginate->Page() ? 'selected' : '') }}>{{ $i }}</option>
                @endif
              @endfor
            </select>          
          </div>
          <span class="text-muted fs-sm mb-2 mt-1 p-1">de {{ $paginate->NumberOfPages() }} página(s)</span>

          @if (!$paginate->IsLast())  
            @if (strlen($criterio)>0)
              <a class="page-link" href="{{ route($route, [$paginate->Next(), $criterio]) }}" aria-label="Next">
                <span aria-hidden="true">
                  <i class="fa fa-angle-right mb-2 mt-1 p-1 text-muted"></i>
                </span>
              </a>            
            @else
              <a class="page-link" href="{{ route($route, $paginate->Next(), $criterio) }}" aria-label="Next">
                <span aria-hidden="true">
                  <i class="fa fa-angle-right mb-2 mt-1 p-1 text-muted"></i>
                </span>
              </a>            
            @endif
    
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

@endif