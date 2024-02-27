@extends("plantillas.principal")

@php
    $titulo = "Cargar beneficiarios";
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('convenios.index') }}">
        Convenios
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
<div class="block block-rounded">

    <div class="block-content">
          
          <div class="block block-rounded">
            <div class="block-content">
              
            <form action="{{ route('convenios.cargar-beneficiarios') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="convenioId" id="convenioId" value="{{ $convenio->getId() }}">

                <div class="input-group">
                        <input class="form-control" type="file" name="archivo" accept=".xlsx, .xls">
                        <button type="submit" class="btn btn-alt-primary">
                            <i class="fa fa-fw fa-upload"></i> Upload
                        </button>                        
                </div>

            </form>

            </div>
          </div>          

</div>

<script src="{{asset('assets/js/plugins/dropzone/min/dropzone.min.js')}}"></script>
@endsection