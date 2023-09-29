<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\orientadores\ActualizarOrientadorUseCase;
use Src\usecase\orientadores\BuscadorOrientadorUseCase;
use Src\usecase\orientadores\BuscarOrientadorPorDocumentoUseCase;
use Src\usecase\orientadores\BuscarOrientadorPorIdUseCase;
use Src\usecase\orientadores\CrearOrientadorUseCase;
use Src\usecase\orientadores\EliminarOrientadorUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\view\dto\OrientadorDto;

class OrientadorController extends Controller
{
    public $listaEPS = array();
    public function __construct() {
        $this->listaEPS = [
            "COOSALUD EPS-S",
            "NUEVA EPS",
            "MUTUAL SER",
            "ALIANSALUD EPS",
            "SALUD TOTAL EPS S.A.",
            "EPS SANITAS",
            "EPS SURA",
            "FAMISANAR",
            "SERVICIO OCCIDENTAL DE SALUD EPS SOS",
            "SALUD MIA",
            "COMFENALCO VALLE",
            "COMPENSAR EPS",
            "EPM - EMPRESAS PUBLICAS DE MEDELLIN",
            "FONDO DE PASIVO SOCIAL DE FERROCARRILES NACIONALES DE COLOMBIA",
            "CAJACOPI ATLANTICO",
            "CAPRESOCA",
            "COMFACHOCO",
            "COMFAORIENTE",
            "EPS FAMILIAR DE COLOMBIA",
            "ASMET SALUD",
            "EMSSANAR E.S.S.",
            "CAPITAL SALUD EPS-S",
            "SAVIA SALUD EPS",
            "DUSAKAWI EPSI",
            "ASOCIACION INDIGENA DEL CAUCA EPSI",
            "ANAS WAYUU EPSI",
            "MALLAMAS EPSI",
            "PIJAOS SALUD EPSI",
            "SALUD BÓLIVAR EPS SAS"
        ];        
        
    }

    public function index() {
        $casoUso = new ListarOrientadoresUseCase();
        $resp = $casoUso->ejecutar();

        return view("orientadores.index", [
            "orientadores" => $resp["data"]
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $listarAreas = new ListarAreasUseCase();
        $rs = $listarAreas->ejecutar();
        $areas = $rs['data'];

        $casoUso = new BuscarOrientadorPorIdUseCase();
        $resp = $casoUso->ejecutar($id);
        $o = $resp['data'];

        return view('orientadores.edit', ["orientador" => [
            'areas' => $areas,
            'id' => $o['id'],
            'nombre' => $o['nombre'],
            'tipoDocumento' => $o['tipo_documento'],
            'documento' => $o['documento'],
            'emailInstitucional' => $o['email_institucional'],
            'emailPersonal' => $o['email_personal'],
            'direccion' => $o['direccion'],
            'eps' => $o['eps'],
            'estado' => $o['estado'],
            'observacion' => $o['observacion'],
            'listaEps' => $this->listaEPS,
        ]]);
    }

    public function buscador() {        
        request()->validate([
            'criterio' => 'required',
        ]);
        
        $criterio = request('criterio');

        $casoUso = new BuscadorOrientadorUseCase();
        $resp = $casoUso->ejecutar($criterio);

        return view("orientadores.index", [
            "orientadores" => $resp["data"],
            "criterio" => $criterio,
        ]); 
    }

    public function create() {
        $listarAreas = new ListarAreasUseCase();
        $rs = $listarAreas->ejecutar();
        $areas = $rs['data'];
                
        return view('orientadores.create', ["orientador" => [
            'areas' => $areas,
            'nombre' => '',
            'tipoDocumento' => '',
            'documento' => '',
            'emailInstitucional' => '',
            'emailPersonal' => '',
            'direccion' => '',
            'eps' => '',
            'estado' => '',
            'observacion' => '',
            'listaEps' => $this->listaEPS,
        ]]);
    }

    public function store() {
        request()->validate([
            'nombre' => 'required',
            'tipoDocumento' => 'required',
            'documento' => 'required|numeric',
            'emailInstitucional' => 'email|nullable',
            'emailPersonal' => 'email|nullable',
            'direccion' => 'nullable',
            'eps' => 'nullable',
            'area' => 'nullable',
            'observacion' => 'nullable'
        ]);        
        
        $orientadorDto = new OrientadorDto();
        $orientadorDto->nombre = request('nombre');
        $orientadorDto->tipoDocumento = request('tipoDocumento');
        $orientadorDto->documento = request('documento');
        
        $orientadorDto->emailPersonal = "";
        if (!is_null(request('emailPersonal'))) {
            $orientadorDto->emailPersonal = request('emailPersonal');
        }

        $orientadorDto->emailInstitucional = "";
        if (!is_null(request('emailInstitucional'))) {
            $orientadorDto->emailInstitucional = request('emailInstitucional');
        }

        $orientadorDto->direccion = "";
        if (!is_null(request('direccion'))) {
            $orientadorDto->direccion = request('direccion');
        }

        $orientadorDto->eps = "";
        if (!is_null(request('eps'))) {
            $orientadorDto->eps = request('eps');
        }        

        $orientadorDto->observacion = "";
        if (!is_null(request('observacion'))) {
            $orientadorDto->observacion = request('observacion');
        }

        $casoUso = new CrearOrientadorUseCase();
        $resp = $casoUso->ejecutar($orientadorDto);

        return redirect()
                    ->route('orientadores.index')
                    ->with('code', $resp['code'])
                    ->with('status', $resp['message']);   
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new EliminarOrientadorUseCase();
        $resp = $casoUso->ejecutar($id);
        return redirect()->route('orientadores.index')
                ->with('code', $resp['code'])
                ->with('status', $resp['message']);
    }
    
    public function update() {
        request()->validate([
            'id' => 'required|numeric',
            'nombre' => 'required',
            'tipoDocumento' => 'required',
            'documento' => 'required|numeric',
            'emailInstitucional' => 'email|nullable',
            'emailPersonal' => 'email|nullable',
            'direccion' => 'nullable',
            'eps' => 'nullable',
            'area' => 'nullable',
            'observacion' => 'nullable'
        ]);        
        
        $orientadorDto = new OrientadorDto();
        $orientadorDto->id = request('id');
        $orientadorDto->nombre = request('nombre');
        $orientadorDto->tipoDocumento = request('tipoDocumento');
        $orientadorDto->documento = request('documento');
        $orientadorDto->estado = true;
        
        $orientadorDto->emailPersonal = "";
        if (!is_null(request('emailPersonal'))) {
            $orientadorDto->emailPersonal = request('emailPersonal');
        }

        $orientadorDto->emailInstitucional = "";
        if (!is_null(request('emailInstitucional'))) {
            $orientadorDto->emailInstitucional = request('emailInstitucional');
        }

        $orientadorDto->direccion = "";
        if (!is_null(request('direccion'))) {
            $orientadorDto->direccion = request('direccion');
        }

        $orientadorDto->eps = "";
        if (!is_null(request('eps'))) {
            $orientadorDto->eps = request('eps');
        }        

        $orientadorDto->observacion = "";
        if (!is_null(request('observacion'))) {
            $orientadorDto->observacion = request('observacion');
        }


        $casoUso = new ActualizarOrientadorUseCase();
        $resp = $casoUso->ejecutar($orientadorDto);
        return redirect()->route('orientadores.index')
                ->with('code', $resp['code'])
                ->with('status', $resp['message']);
    }     
}
