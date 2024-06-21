<?php

namespace App\Http\Controllers;

use App\Http\Requests\BuscarParticipantePorDocumento;
use App\Http\Requests\FormularioTramite;
use App\Http\Requests\RealizarCambioDeCursoOGrupo;
use Src\domain\Calendario;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\cambios_traslados\BuscadorCambiosYTrasladosUseCase;
use Src\usecase\cambios_traslados\CambiarCursoOGrupoUseCase;
use Src\usecase\cambios_traslados\ListarCambiosYTrasladosUseCase;
use Src\usecase\formularios\BuscarFormularioPorNumeroUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\grupos\ListarGruposDisponiblesParaMatriculaUseCase;
use Src\usecase\participantes\BuscadorParticipantesUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;

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

    public function formularioDeTramite($numero_formulario) {

        // $req = $req->validated();    

        $formulario = (new BuscarFormularioPorNumeroUseCase)->ejecutar($numero_formulario);
        if (!$formulario->existe()) {
            return redirect()->route('cambios-traslados.index')->with('code', "404")->with('status', "Formulario no encontrado.");
        }

        $areas = [];
        $vista_segun_motivo = '_form_cambio_de_curso_grupo';
        // if ($req['motivo'] == "traslado") {
        //     $vista_segun_motivo = '_form_traslado';

        // } else if ($req['motivo'] == "aplazamiento") { 
        //     $vista_segun_motivo = '_form_aplazamiento';

        // } else if ($req['motivo'] == "cancelacion") { 
        //     $vista_segun_motivo = '_form_cancelacion';

        // } else if ($req['motivo'] == "cambio") { 
        //     $areas = (new ListarAreasUseCase)->ejecutar();
        // }
        $areas = (new ListarAreasUseCase)->ejecutar();


        return view('cambios-traslados.form-tramite', [
            'formulario' => $formulario,
            'labelMotivo' => ListaDeValor::tagMotivoCambioYTraslado('cambio'),
            'motivo' => 'cambio',
            'vista_segun_motivo' => $vista_segun_motivo,
            'areas' => $areas,
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

    public function guardarTramite(RealizarCambioDeCursoOGrupo $req) {

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
            'decision_sobre_pago' => $decisionSobrePago,
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
}
