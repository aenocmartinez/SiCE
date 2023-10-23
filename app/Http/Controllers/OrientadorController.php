<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarOrientador;

use Src\domain\Orientador;
use Src\infraestructure\util\ListaDeValor;
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
        $this->listaEPS = ListaDeValor::eps();
        
    }

    public function index() {
        $casoUso = new ListarOrientadoresUseCase();
        $orientadores = $casoUso->ejecutar();
        return view("orientadores.index", compact('orientadores'));
    }

    public function edit($id) {

        $this->validarParametroId($id);

        $casoUso = new BuscarOrientadorPorIdUseCase();
        $orientador = $casoUso->ejecutar($id);

        if (!$orientador->existe()) {
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Orientador no encontrado");
        }

        $nivelesEstudio = explode(',', env('APP_NIVEL_ESTUDIO'));        

        return view('orientadores.edit', [
            'orientador' => $orientador, 
            'listaEps' => ListaDeValor::eps(),
            'nivelesEstudio' => $nivelesEstudio,
        ]);
    }

    public function editAreas($idOrientador) {
        $this->validarParametroId($idOrientador);

        $casoUsoListarAreas = new ListarAreasUseCase();

        $casoUso = new BuscarOrientadorPorIdUseCase();
        $orientador = $casoUso->ejecutar($idOrientador);

        return view('orientadores.addAreas', [
            'orientador' => $orientador,
            'eps' =>  ListaDeValor::eps(),
            'areas' => $casoUsoListarAreas->ejecutar(), 
        ]);
    }

    public function removeArea($idOrientador, $idArea) {    
        
        $this->validarParametroId($idOrientador);
        $this->validarParametroId($idArea);
        
        $casoUso = new QuitarAreaAOrientadorUseCase();
        $response = $casoUso->ejecutar($idOrientador, $idArea);

        return redirect()->route('orientadores.editAreas', [$idOrientador])->with('code', $response->code)->with('status', $response->message);
    }

    public function addArea() {
        request()->validate([
            'idOrientador' => 'required|numeric',
            'area' => 'required|numeric'
        ]);
        
        $idOrientador = request('idOrientador');
        $idArea = request('area');

        $casoUso = new AgregarAreaAOrientadorUseCase();
        $response = $casoUso->ejecutar($idOrientador, $idArea);

        return redirect()->route('orientadores.editAreas', [request('idOrientador')])
                         ->with('code', $response->code)->with('status', $response->message);
    }    

    public function buscador() {        
        request()->validate([
            'criterio' => 'required',
        ]);
        
        $criterio = request('criterio');

        $casoUso = new BuscadorOrientadorUseCase();
        $orientadores = $casoUso->ejecutar($criterio);

        return view("orientadores.index", [
            "orientadores" => $orientadores,
            "criterio" => $criterio,
        ]); 
    }

    public function create() {    
        $nivelesEstudio = explode(',', env('APP_NIVEL_ESTUDIO'));
        return view('orientadores.create', [
            'orientador' => new Orientador(),
            'listaEps' => ListaDeValor::eps(),
            'nivelesEstudio' => $nivelesEstudio,
        ]);
    }

    public function store(GuardarOrientador $request) {
        $request->validated();
        $orientadorDto = $this->hydrateDto();   
        
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
        $request->validated();
        $orientadorDto = $this->hydrateDto();
       
        $casoUso = new ActualizarOrientadorUseCase();
        $response = $casoUso->ejecutar($orientadorDto);

        return redirect()->route('orientadores.index')->with('code', $response->code)->with('status', $response->message);
    }     

    private function hydrateDto(): OrientadorDto {

        $orientadorDto = new OrientadorDto();

        $orientadorDto->id = request('id');
        $orientadorDto->nombre = request('nombre');
        $orientadorDto->tipoDocumento = request('tipoDocumento');
        $orientadorDto->documento = request('documento');
        $orientadorDto->fechaNacimiento = request('fecNacimiento');
        $orientadorDto->nivelEducativo = request('nivelEstudio');
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
        
        return $orientadorDto;
    }

    private function validarParametroId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return redirect()->route('orientadores.index')->with('code', "401")->with('status', "parámetro no válido");
        }        
    }

    public function show($id) {
        $orientador = (new BuscarOrientadorPorIdUseCase)->ejecutar($id);
        if (!$orientador->existe()) {
            return redirect()->route('cursos.index')->with('code', "404")->with('status', "Orientador no encontrado");
        }

        return view('orientadores.moreInfo', [
            'orientador' => $orientador, 
        ]);

        dd($orientador);
    }
}
