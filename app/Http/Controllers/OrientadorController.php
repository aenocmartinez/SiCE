<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgregarAreaOrientador;
use App\Http\Requests\GuardarOrientador;
use App\Http\Requests\RegistrarAsistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Src\dao\mysql\CalendarioDao;
use Src\dao\mysql\GrupoDao;
use Src\dao\mysql\OrientadorDao;
use Src\domain\Calendario;
use Src\domain\Orientador;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\calendarios\BuscarCalendarioPorIdUseCase;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\grupos\CancelarGrupoUseCase;
use Src\usecase\orientadores\ActualizarOrientadorUseCase;
use Src\usecase\orientadores\BuscadorOrientadorUseCase;
use Src\usecase\orientadores\BuscarOrientadorPorIdUseCase;
use Src\usecase\orientadores\CrearOrientadorUseCase;
use Src\usecase\orientadores\EliminarOrientadorUseCase;
use Src\usecase\orientadores\ListarOrientadoresPaginadoUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\orientadores\ObtenerDatosFormularioReporteAsistenciaUseCase;
use Src\usecase\orientadores\ObtenerFormularioRegistroAsistenciaUseCase;
use Src\usecase\orientadores\RegistrarAsistenciaUseCase;
use Src\view\dto\OrientadorDto;

class OrientadorController extends Controller 
{
    public $listaEPS = array();
    public function __construct() {
        $this->listaEPS = ListaDeValor::eps();
        
    }

    public function index() {
        $casoUso = new ListarOrientadoresUseCase();
        $orientadores = $casoUso->ejecutar();
        return view("orientadores.index", compact('orientadores'));
    }

    public function edit($id) {

        $this->validarParametroId($id);



        $orientador = (new BuscarOrientadorPorIdUseCase())->ejecutar($id);

        if (!$orientador->existe()) {
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Orientador no encontrado");
        }

        $nivelesEstudio = explode(',', env('APP_NIVEL_ESTUDIO'));
        $listaRangoSalarial = explode(',', env('APP_RANGO_SALARIAL'));   

        return view('orientadores.edit', [
            'orientador' => $orientador, 
            'listaEps' => ListaDeValor::eps(),
            'nivelesEstudio' => $nivelesEstudio,
            'listaRangoSalarial' => $listaRangoSalarial,
            'areas' => (new ListarAreasUseCase)->ejecutar(),
        ]);
    }

    public function create() {
        $nivelesEstudio = explode(',', env('APP_NIVEL_ESTUDIO'));
        $listaRangoSalarial = explode(',', env('APP_RANGO_SALARIAL'));
        return view('orientadores.create', [
            'orientador' => new Orientador(),
            'listaEps' => ListaDeValor::eps(),
            'nivelesEstudio' => $nivelesEstudio,
            'listaRangoSalarial' => $listaRangoSalarial,
            'areas' => (new ListarAreasUseCase)->ejecutar(),
        ]);
    }

    public function store(GuardarOrientador $request) {
        $data = $request->validated();        
        $orientadorDto = $this->hydrateDto($data);
        
        $casoUso = new CrearOrientadorUseCase();
        $response = $casoUso->ejecutar($orientadorDto);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function delete($id) {
        $this->validarParametroId($id);

        $casoUso = new EliminarOrientadorUseCase();
        $response = $casoUso->ejecutar($id);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }
    
    public function update(GuardarOrientador $request) {
        $data = $request->validated();
        $orientadorDto = $this->hydrateDto($data);
       
        $casoUso = new ActualizarOrientadorUseCase();
        $response = $casoUso->ejecutar($orientadorDto);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }     

    private function hydrateDto($data): OrientadorDto {

        $orientadorDto = new OrientadorDto();

        if (isset($data['id'])) {
            $orientadorDto->id = $data['id'];
        }

        $orientadorDto->nombre = $data['nombre'];
        $orientadorDto->tipoDocumento = $data['tipoDocumento'];
        $orientadorDto->documento = $data['documento'];
        $orientadorDto->fechaNacimiento = $data['fecNacimiento'];
        $orientadorDto->nivelEducativo = $data['nivelEstudio'];
        $orientadorDto->rangoSalarial = $data['rangoSalarial'];
        $orientadorDto->areas = $data['areas'];
        
        
        $orientadorDto->estado = true;
        
        $orientadorDto->emailPersonal = "";
        if (!is_null($data['emailPersonal'])) {
            $orientadorDto->emailPersonal = $data['emailPersonal'];
        }

        $orientadorDto->emailInstitucional = "";
        if (!is_null($data['emailInstitucional'])) {
            $orientadorDto->emailInstitucional = $data['emailInstitucional'];
        }

        $orientadorDto->direccion = "";
        if (!is_null($data['direccion'])) {
            $orientadorDto->direccion = $data['direccion'];
        }

        $orientadorDto->eps = "";
        if (!is_null($data['eps'])) {
            $orientadorDto->eps = $data['eps'];
        }        

        $orientadorDto->observacion = "";
        if (!is_null($data['observacion'])) {
            $orientadorDto->observacion = $data['observacion'];
        }
        
        return $orientadorDto;
    }

    private function validarParametroId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('orientadores.index')->with('code', "401")->with('status', "parámetro no válido");
        }        
    }

    public function show($id) 
    { 
        $periodo = Calendario::Vigente();
        if (!is_null(request('periodo')))  
        {
            $periodo = (new BuscarCalendarioPorIdUseCase)->ejecutar(request('periodo'));
        }

        $orientador = (new BuscarOrientadorPorIdUseCase)->ejecutar($id);
        if (!$orientador->existe()) {
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Orientador no encontrado");
        }

        $orientador->setGruposPorCalendario($periodo->getId());


        return view('orientadores.moreInfo', [
            'orientador' => $orientador, 
            'dias' => ListaDeValor::diasSemana(),
            'jornadas' => ListaDeValor::jornadas(),
            'areas' => (new ListarAreasUseCase)->ejecutar(),
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'periodoFiltro' => $periodo,
        ]);
    }

    public function listarPaginado($page=1) {              
        return view("orientadores.index", [
            'paginate' => (new ListarOrientadoresPaginadoUseCase)->ejecutar($page),
        ]);        
    }

    public function paginadorBuscador($page, $criterio) {
        return view("orientadores.index", [
            "paginate" => (new BuscadorOrientadorUseCase)->ejecutar($criterio, $page), 
            "criterio" => $criterio
        ]);         
    }

    public function buscador() { 
             
        $criterio = '';
        if (!is_null(request('criterio'))) {
            $criterio = request('criterio');
        } else {
            return redirect()->route('orientadores.index');
        }

        return view("orientadores.index", [
            "paginate" => (new BuscadorOrientadorUseCase)->ejecutar($criterio),
            "criterio" => $criterio,
        ]); 
    }   
    
    public function cancelar($orientadorId, $grupoId) {        
        $response = (new CancelarGrupoUseCase)->ejecutar($grupoId);
        return redirect()->route('orientadores.moreInfo', $orientadorId)->with('code', $response->code)->with('status', $response->message);        
    }  
    
    public function formularioAsistencia() {

        $response = (new ObtenerFormularioRegistroAsistenciaUseCase)->ejecutar();

        if ($response->code != 200) {
            return redirect()->route('dashboard')->with('code', $response->code)->with('status', $response->message);
        }

        $datosFormulario = $response->data;      
        return view('orientadores.registrarAsistencia',[
            'datosFormulario' => $datosFormulario,
        ]);
        

    }

    public function registrarAsistencia(RegistrarAsistencia $req) {

        $datos = $req->validated();

        $grupoID            = $datos['grupo_id'];
        $sesion             = $datos['sesion'];
        $listaAsistencia    = $datos['asistencias'];

        $response = (new RegistrarAsistenciaUseCase)->ejecutar($grupoID, $sesion, $listaAsistencia);

        return redirect()->route('asistencia.formulario')->with('code', $response->code)->with('status', $response->message);
    }

    public function formularioReportePorCurso() {

        $response = (new ObtenerDatosFormularioReporteAsistenciaUseCase)->ejecutar();

        return view('orientadores.formulario-reportes', [
            'datos' => $response->data['datos']
        ]);
    }

    public function formularioReporteParticipante() {
        
        $response = (new ObtenerDatosFormularioReporteAsistenciaUseCase)->ejecutar();
        // dd($response->data['datos']);
        return view('orientadores.asistencia-participante', [
            'datos' => $response->data['datos'],
        ]);
    }
    
    /**
     * GET /asistencia/periodos-json
     * Retorna [ { id, nombre }, ... ]
     */
    public function periodosJson()
    {
        $calDao = new \Src\dao\mysql\CalendarioDao();
        return response()->json($calDao->listarCalendariosLivianos());
    }


    /**
     * GET /asistencia/grupos-json?periodo_id=#
     * Retorna los grupos del orientador autenticado en ese periodo.
     */
    public function gruposPorPeriodoJson(Request $request)
    {
        $periodoId = (int) $request->query('periodo_id');
        if (!$periodoId) return response()->json(['message' => 'periodo_id es requerido'], 422);

        $orientadorId = auth()->user()->orientador_id;

        $grupoDao = new \Src\dao\mysql\GrupoDao();
        return response()->json($grupoDao->listarGruposPorPeriodoYOrientador($periodoId, $orientadorId));
    }

    /**
     * GET /asistencia/sesiones-json?grupo_id=#
     * Retorna las sesiones existentes (num y fecha) del grupo.
     */
    public function sesionesPorGrupoJson(Request $request)
    {
        $grupoId = (int) $request->query('grupo_id');
        if (!$grupoId) return response()->json(['message' => 'grupo_id es requerido'], 422);

        $grupoDao = new \Src\dao\mysql\GrupoDao();
        return response()->json($grupoDao->listarSesionesDeGrupo($grupoId));
    }

    /**
     * GET /asistencia/asistencia-json?grupo_id=#&sesion=#
     * Retorna meta del grupo y la lista de participantes con presente/ausente y convenio.
     */
    public function asistenciaPorSesionJson(Request $request)
    {
        $grupoId = (int) $request->query('grupo_id');
        $sesion  = (int) $request->query('sesion');
        if (!$grupoId || !$sesion) return response()->json(['message' => 'grupo_id y sesion son requeridos'], 422);

        $grupoDao = new \Src\dao\mysql\GrupoDao();
        return response()->json($grupoDao->obtenerAsistenciaPorSesionDetalle($grupoId, $sesion));
    } 


    public function formularioAsistenciaPorSesion()
    {
        return view('orientadores.consultar_asistencia_por_sesion');
    }
    
    
}
