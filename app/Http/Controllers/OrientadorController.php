<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Src\infraestructure\util\Validador;
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
    public function index() {
        $casoUso = new ListarOrientadoresUseCase();
        $resp = $casoUso->ejecutar();
        if ($resp['code'] != "200") {

        }

        return view("orientadores", [
            "orientadores" => $resp["data"]
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new BuscarOrientadorPorIdUseCase();
        $resp = $casoUso->ejecutar($id);

        echo json_encode($resp);
    }

    public function buscarPorDocumento($tipoDocumento, $documento) {
        $casoUso = new BuscarOrientadorPorDocumentoUseCase();
        $resp = $casoUso->ejecutar($tipoDocumento, $documento);
        echo json_encode($resp);
    }

    public function buscador($criterio) {
        $casoUso = new BuscadorOrientadorUseCase();
        $resp = $casoUso->ejecutar($criterio);
        echo json_encode($resp);
    }

    public function create($id) {
        $orientadorDto = new OrientadorDto();
        $orientadorDto->nombre = "Adriana Patricia Nieto Triviño";
        $orientadorDto->tipoDocumento = "CC";
        $orientadorDto->documento = "77050505";
        $orientadorDto->emailInstitucional = "apnieto@unicolmayor.edu.co";
        $orientadorDto->emailPersonal = "apnieto@gmail.com";
        $orientadorDto->direccion = "Calle 150A # 102B - 47";
        $orientadorDto->eps = "Sanitas EPS";
        $orientadorDto->observacion = "Observaciones generales";

        $casoUso = new CrearOrientadorUseCase();
        $resp = $casoUso->ejecutar($orientadorDto);

        echo json_encode($resp);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            echo json_encode(["code" => "401", "message" => "parámetro no válido"]);
        }

        $casoUso = new EliminarOrientadorUseCase();
        $resp = $casoUso->ejecutar($id);
        echo json_encode($resp);
    }
    
    public function update($id) {
        $orientadorDto = new OrientadorDto();
        $orientadorDto->id = $id;
        $orientadorDto->nombre = "José Joaquín Amara";
        $orientadorDto->tipoDocumento = "CC";
        $orientadorDto->documento = "77050505";
        $orientadorDto->emailInstitucional = "jamara@unicolmayor.edu.co";
        $orientadorDto->emailPersonal = "jamara@gmail.com";
        $orientadorDto->direccion = "Calle 150A # 102B - 47";
        $orientadorDto->eps = "Sanitas EPS";
        $orientadorDto->estado = true;
        $orientadorDto->observacion = "Observaciones generales bastante generales";

        $casoUso = new ActualizarOrientadorUseCase();
        $respuesta = $casoUso->ejecutar($orientadorDto);
        echo json_encode($respuesta);
    }     
}
