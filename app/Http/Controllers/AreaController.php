<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Src\usecase\areas\ListarAreasUseCase;
use Src\domain\Area;

class AreaController extends Controller
{
    public function index() {
        $useCase = new ListarAreasUseCase();
        $lista = $useCase->execute();
        dd($lista);
    }
}
