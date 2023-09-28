<?php

namespace App\Http\Controllers;


use Src\infraestructure\util\Validador;
use Src\usecase\salones\ActualizarSalonUseCase;
use Src\usecase\salones\BuscadorSalonesUseCase;
use Src\usecase\salones\BuscarSalonPorIdUseCase;
use Src\usecase\salones\CrearSalonUseCase;
use Src\usecase\salones\EliminarSalonUseCase;
use Src\usecase\salones\ListarSalonesUseCase;
use Src\view\dto\SalonDto;

class SalonController extends Controller
{
    public function index() {
        $casoUso = new ListarSalonesUseCase();
        $resp = $casoUso->ejecutar();
        
        if ($resp["code"] != "200") {

        }

        return view("salones", [
            "salones" => $resp["data"]
        ]);
    }

    public function buscarPorId($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return ["code" => "401", "message" => "parámetro no válido"];
        }

        $casoUso = new BuscarSalonPorIdUseCase();
        $resp = $casoUso->ejecutar($id);
        echo json_encode($resp);
    }

    public function buscador($criterio) {
        $casoUso = new BuscadorSalonesUseCase();
        $resp = $casoUso->ejecutar($criterio);
        echo json_encode($resp);
    }

    public function create($nombre, $capacidad, $disponible) {
        $salonDto = new SalonDto();
        $salonDto->nombre = $nombre;
        $salonDto->capacidad = $capacidad;
        $salonDto->disponible = $disponible;

        $casoUso = new CrearSalonUseCase();
        $resp = $casoUso->ejecutar($salonDto);
        echo json_encode($resp);
    }

    public function delete($id) {
        $esValido = Validador::parametroId($id);
        if (!$esValido) {
            return ["code" => "401", "message" => "parámetro no válido"];
        }

        $casoUso = new EliminarSalonUseCase();
        $resp = $casoUso->ejecutar($id);
        echo json_encode($resp);
    }

    public function update($id, $nombre, $capacidad, $disponible) {
        $salonDto = new SalonDto();
        $salonDto->id = $id;
        $salonDto->nombre = $nombre;
        $salonDto->capacidad = $capacidad;
        $salonDto->disponible = $disponible;

        $casoUso = new ActualizarSalonUseCase();
        $resp = $casoUso->ejecutar($salonDto);
        echo json_encode($resp);
    }

}
