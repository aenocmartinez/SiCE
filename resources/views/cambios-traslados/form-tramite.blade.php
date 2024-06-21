@extends("plantillas.principal")

@php
    $titulo = "Nuevo trámite";    
@endphp

@section("title", $titulo)

@section("description", "")

@section("seccion")
    <a class="link-fx" href="{{ route('cambios-traslados.index') }}">
        Volver al listado
    </a>
@endsection

@section("subseccion", $titulo)

@section("content")
    <form action="{{ route('cambios-y-traslados.guardar-tramite') }}" method="POST">
        @csrf
        <input type="hidden" name="numero_formulario" id="numero_formulario" value="{{ $formulario->getNumero() }}">
        <input type="hidden" name="grupoId" id="grupoId" value="">
        <input type="hidden" name="accion" id="accion" value="cambio">
        <input type="hidden" name="estado" id="estado" value="{{ $formulario->getEstado() }}">
        <input type="hidden" name="costo_curso_actual" id="costo_curso_actual" value="{{ $formulario->getTotalAPagar() }}">
        <input type="hidden" name="tiene_convenio" id="tiene_convenio" value="{{ $formulario->tieneConvenio() }}">
        <input type="hidden" name="porcentaje_descuento" id="porcentaje_descuento" value="{{ $formulario->getConvenioDescuento() }}">

        @include('cambios-traslados._form_tramites')
        
    </form>


<link rel="stylesheet" href="{{asset('assets/js/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" id="css-main" href="{{asset('assets/css/oneui.min.css')}}">


<script src="{{asset('assets/js/oneui.app.min.js')}}"></script>

<script src="{{asset('assets/js/lib/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js')}}"></script>
<script>One.helpersOnLoad(['jq-select2']);</script>

<script>

    $(document).ready(function(){
                
        $("#area_id").change(function() { 
            $("#saldo-a-favor").hide(); 
            $("#saldo-en-contra").hide();

            const area_id = $('#area_id').val();
            var url = "{{ route('cambios-traslados.cursos-para-matricular', ['area_id' => ':paramId']) }}";
            url = url.replace(':paramId', area_id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(resp) {
                    $("#nuevo_curso").html(resp);
                }            
            });        
        });

        $("#nuevo_curso").change(function(){            
            $("#saldo-a-favor").hide();
            $("#saldo-en-contra").hide();

            const value = $('#nuevo_curso').val();
            data = value.split('@');
            if (data.length == 0) {
                return ;
            }
            const grupo_id                      = data[0];
            const costo_nuevo_curso             = data[1];
            const valor_pagado_curso_inicial    = $('#costo_curso_actual').val(); 
            const tiene_convenio                = $('#tiene_convenio').val();
            const porcentaje_descuento          = $("#porcentaje_descuento").val();                        
            var   valor_descuento               = 0;
            $("#grupoId").val(grupo_id);

            if (tiene_convenio) {
                valor_descuento = costo_nuevo_curso * (porcentaje_descuento/100);                
            }
            
            var nuevo_valor_a_pagar = costo_nuevo_curso - valor_descuento;            
            var aux_valor_saldo_a_favor = valor_pagado_curso_inicial - nuevo_valor_a_pagar;
            
            // El saldo es cero (0)
            if (aux_valor_saldo_a_favor == 0) {
                return ;
            }

            //Saldo en contra
            if (aux_valor_saldo_a_favor < 0) {
                $("#saldo-en-contra").show();
                $("#valor-saldo-en-contra").text(formatoDeMoneda(aux_valor_saldo_a_favor*-1));
            }

            //Saldo a favor
            if (aux_valor_saldo_a_favor > 0) {
                if ($("#estado").val() == 'Pendiente de pago') {
                    return ;
                }
                $("#saldo-a-favor").show();
                $("#valor-saldo-a-favor").text(formatoDeMoneda(aux_valor_saldo_a_favor));
            }           

        });

    });

    function formatoDeMoneda(numero) {
        var opciones = { style: 'currency', currency: 'COP', minimumFractionDigits: 0, maximumFractionDigits: 0 };
        var numeroFormateado = numero.toLocaleString('es-CO', opciones);
        return numeroFormateado.replace('COP', '').trim() + ' COP';        
    }

</script>

@endsection