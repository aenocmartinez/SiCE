<?php

namespace App\Http\Controllers;

use App\Http\Requests\AplazarInscripcion;
use App\Http\Requests\BuscarParticipantePorDocumento;
use App\Http\Requests\HacerDevolucionDeUnInscripion;
use App\Http\Requests\RealizarCambioDeCursoOGrupo;
use Src\domain\Calendario;
use Src\infraestructure\util\FormatoFecha;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\cambios_traslados\BuscadorCambiosYTrasladosUseCase;
use Src\usecase\cambios_traslados\CambiarCursoOGrupoUseCase;
use Src\usecase\cambios_traslados\ListarCambiosYTrasladosUseCase;
use Src\usecase\formularios\AplazarInscripcionUseCase;
use Src\usecase\formularios\BuscarFormularioPorNumeroUseCase;
use Src\usecase\formularios\DevolucionInscripcionUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\ListarGruposDisponiblesParaMatriculaUseCase;
use Src\usecase\participantes\BuscadorParticipantesUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\view\dto\AplazarInscripcionDto;
use Src\view\dto\DevolucionInscripcionDto;

class CambiosTrasladosController extends Controller
{
    public function paginar($page=1) {        
        return view("cambios-traslados.index", [
            'paginate' => (new ListarCambiosYTrasladosUseCase)->ejecutar($page)
        ]);
    }

    public function buscadorCambiosYTraslados(){
        $criterio = '';
        if (!is_null(request('criterio'))) {
            $criterio = request('criterio');
        }

        if (strlen($criterio)==0) {
            return redirect()->route('cambios-traslados.index');
        }


        return view("cambios-traslados.index", [
            "paginate" => (new BuscadorCambiosYTrasladosUseCase)->ejecutar($criterio),
            "criterio" => $criterio,
        ]);         
    }
    
    public function buscadorCambiosYTrasladosPaginados($page, $criterio) {
        
        if (strlen($criterio)==0) {
            return redirect()->route('cambios-traslados.index');
        }
        dd("Buscador paginador => ".$criterio);

        return view("participantes.index", [
            "paginate" => (new BuscadorParticipantesUseCase)->ejecutar($criterio, $page),
            "criterio" => $criterio,
        ]);          
    }

    public function create() {
        return view('cambios-traslados.create', [
            'tipo_documentos' => ListaDeValor::tipoDocumentos(),
        ]);
    }

    public function buscarParticipantePorDocumento(BuscarParticipantePorDocumento $req) {
        
        if (!Calendario::existeCalendarioVigente()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "No existe periodo académico vigente.");
        }

        $param = $req->validated();        
        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($param['tipoDocumento'], $param['documento']);
        if (!$participante->existe()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "Participante no encontrado.");
        }

        return view('cambios-traslados.info-participante', [
            'participante' => $participante,
            'motivos_de_cambios' => ListaDeValor::motivosCambiosYTraslados(),
        ]);
    } 

    public function listarCursosParaMatricular($area_id) {

        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "No existe periodo académico vigente.");
        }

        $grupos = (new ListarGruposDisponiblesParaMatriculaUseCase)->ejecutar($periodo->getId(), $area_id);
        
        return view('cambios-traslados._lista_grupos_para_matricular', [
            'grupos' => $grupos,
        ]);
    }

    public function formularioDeTramite($numero_formulario, $motivo) {    

        $mapa_vista_por_motivo["aplazamiento"] = "form_aplazamiento";
        $mapa_vista_por_motivo["cambio"] = "form_cambio_de_curso_grupo";
        $mapa_vista_por_motivo["devolucion"] = "form_devolucion";
        
        $formulario = (new BuscarFormularioPorNumeroUseCase)->ejecutar($numero_formulario);
        if (!$formulario->existe()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "Formulario no encontrado.");
        }
         
        $periodo = Calendario::Vigente();
        if (!$periodo->existe()) {                
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "Periodo no válido.");
        }

        $params['formulario'] = $formulario;
        $params['periodo'] = $periodo;

        if ($motivo == "cambio") {

            $params['labelMotivo'] = ListaDeValor::tagMotivoCambioYTraslado('cambio');
            $params['motivo'] = 'cambio';
            $params['areas'] = (new ListarAreasUseCase)->ejecutar();  

        } else if ($motivo == "aplazamiento") {

            $params['fec_caducidad'] = FormatoFecha::sumarMesesAUnaFecha($periodo->getFechaFinal(), 10);
        } else if ($motivo == "devolucion") {

            $params['posibles_causas_devolucion'] = ListaDeValor::origenDevoluciones();            
        }

        return view('cambios-traslados.'.$mapa_vista_por_motivo[$motivo], $params);
    }

    public function realizarCambioDeGrupo(RealizarCambioDeCursoOGrupo $req) 
    {   
        $data = $req->validated();  
        
        $formulario = (new BuscarFormularioPorNumeroUseCase)->ejecutar($data['numero_formulario']);
        if (!$formulario->existe()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "No existe el formulario.");
        }

        $nuevoGrupo = (new BuscarGrupoPorIdUseCase)->ejecutar($data['grupoId']);
        if (!$nuevoGrupo->existe()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "No existe el grupo.");
        }

        $decisionSobrePago = 'sin novedad';
        if (isset($data['decision_sobre_pago'])) {
            $decisionSobrePago = $data['decision_sobre_pago'];
        }

        $datosComplementarios = [
            'justificacion' => $data['justificacion'], 
            'accion' => 'Cambio de grupo', 
            'decision_sobre_pago' => $decisionSobrePago
        ];

        $resultado = (new CambiarCursoOGrupoUseCase)->ejecutar($formulario, $nuevoGrupo, $datosComplementarios);
        
        $code = '500';
        $status = 'Ha ocurrido un error en el sistema, vuelva a intentarlo más tarde.';
        if ($resultado) {
            $code = '200';
            $status = 'El procedimiento se ha realizado con éxito.';
        }

        return redirect()->route('cambios-traslados.index')->with('code', $code)->with('status', $status);
    }

    public function aplazarUnaInscripcion(AplazarInscripcion $req) {

        $data = $req->validated();

        $dataDto = new AplazarInscripcionDto();
        $dataDto->formularioId = $data['formulario_id'];
        $dataDto->numeroFormulario = $data['numero_formulario'];
        $dataDto->participanteId = $data['participante_id'];
        $dataDto->justifiacion = $data['justificacion'];
        $dataDto->fechaCaducidad = $data['fec_caducidad'];
        $dataDto->saldoAFavor = $data['saldo_a_favor'];
        $dataDto->calendarioId = $data['calendario_id'];

        $existoso = (new AplazarInscripcionUseCase)->ejecutar($dataDto);

        $code = 200;
        $status = "Proceso realizado con éxito";
        if (!$existoso) {
            $code = 500;
            $status = "Ha ocurrido un error en el sistema, inténtelo más tarde.";
        }

        return redirect()->route('cambios-traslados.index')->with('code', $code)->with('status', $status);       
    }

    public function hacerDevolucionAUnaInscripcion(HacerDevolucionDeUnInscripion $req) {
        $data = $req->validated();
        $dataDto = new DevolucionInscripcionDto();
        $dataDto->formularioId = $data['formulario_id'];
        $dataDto->numeroFormulario = $data['numero_formulario'];
        $dataDto->participanteId = $data['participante_id'];
        $dataDto->justifiacion = $data['justificacion'];
        $dataDto->origen = $data['origen'];
        $dataDto->valorDevolucion = $data['valor_devolucion'];
        $dataDto->porcentaje = $data['porcentaje'];
        $dataDto->calendarioId = $data['calendario_id'];

        $existoso = (new DevolucionInscripcionUseCase)->ejecutar($dataDto);
        $code = 200;
        $status = "Proceso realizado con éxito";
        if (!$existoso) {
            $code = 500;
            $status = "Ha ocurrido un error en el sistema, inténtelo más tarde.";
        }

        return redirect()->route('cambios-traslados.index')->with('code', $code)->with('status', $status);          
    }
}
