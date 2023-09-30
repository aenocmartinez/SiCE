<?php

namespace App\Http\Controllers;

use Src\infraestructure\util\Validador;
use Src\usecase\areas\ListarAreasUseCase;
use Src\usecase\orientadores\ActualizarOrientadorUseCase;
use Src\usecase\orientadores\AgregarAreaAOrientadorUseCase;
use Src\usecase\orientadores\BuscadorOrientadorUseCase;
use Src\usecase\orientadores\BuscarOrientadorPorIdUseCase;
use Src\usecase\orientadores\CrearOrientadorUseCase;
use Src\usecase\orientadores\EliminarOrientadorUseCase;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\orientadores\QuitarAreaAOrientadorUseCase;
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

    public function edit($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new BuscarOrientadorPorIdUseCase();
        $resp = $casoUso->ejecutar($id);
        $o = $resp['data'];

        return view('orientadores.edit', ["orientador" => [
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

    public function editAreas($idOrientador) {
        $esValido = Validador::parametroId($idOrientador);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUsoListarAreas = new ListarAreasUseCase();
        $areas = $casoUsoListarAreas->ejecutar();

        $casoUso = new BuscarOrientadorPorIdUseCase();
        $resp = $casoUso->ejecutar($idOrientador);
        $o = $resp['data'];
        

        return view('orientadores.addAreas', ["orientador" => [
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
            'areas' => $o['areas'],
        ], 
        'areas' => $areas['data'], 
        ]);
    }

    public function removeArea($idOrientador, $idArea) {                
        $esValido = Validador::parametroId($idOrientador);
        if (!$esValido) 
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        
        $esValido = Validador::parametroId($idArea);
        if (!$esValido) 
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        
        $casoUso = new QuitarAreaAOrientadorUseCase();
        $casoUso->ejecutar($idOrientador, $idArea);

        return redirect()->route('orientadores.editAreas', [$idOrientador]);
    }

    public function addArea() {
        request()->validate([
            'idOrientador' => 'required|numeric',
            'area' => 'required|numeric'
        ]);
        
        $idOrientador = request('idOrientador');
        $idArea = request('area');

        $casoUso = new AgregarAreaAOrientadorUseCase();
        $casoUso->ejecutar($idOrientador, $idArea);

        return redirect()->route('orientadores.editAreas', [request('idOrientador')]);
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
        return view('orientadores.create', ["orientador" => [
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
        ]
    ]);
    }

    public function store() {
        request()->validate([
            'nombre' => 'required',
            'tipoDocumento' => 'required',
            'documento' => 'required|numeric',
            'emailInstitucional' => 'regex:/^.+@unicolmayor\.edu\.co$/i|nullable',
            'emailPersonal' => 'email|nullable',
            'direccion' => 'nullable',
            'eps' => 'nullable',
            'area' => 'nullable',
            'observacion' => 'nullable'
        ],[
            'emailInstitucional.regex' => 'Sólo se permiten email terminados en @unicolmayor.edu.co',
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
