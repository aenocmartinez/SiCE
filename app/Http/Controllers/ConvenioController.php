<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarConvenio;
use Illuminate\Http\Request;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\infraestructure\util\Validador;
use Src\usecase\calendarios\ListarCalendariosUseCase;
use Src\usecase\convenios\ActualizarConvenioUseCase;
use Src\usecase\convenios\BuscarConvenioPorIdUseCase;
use Src\usecase\convenios\CargarBeneficiariosConvenioUseCase;
use Src\usecase\convenios\CrearConvenioUseCase;
use Src\usecase\convenios\EliminarConvenioUseCase as ConveniosEliminarConvenioUseCase;
use Src\usecase\convenios\FacturarConvenioUseCase;
use Src\usecase\convenios\ListarConveniosPorPeriodoUseCase;
use Src\usecase\convenios\ListarConveniosUseCase;
use Src\view\dto\ConvenioDto;

class ConvenioController extends Controller
{

    public function index()
    {
        $periodo = Calendario::Vigente();
        $periodoSeleccionado = 0;

        $convenios = (new ListarConveniosUseCase)->ejecutar();
        if ($periodo->existe()) {
            $periodoSeleccionado = $periodo->getId();
            $convenios = (new ListarConveniosPorPeriodoUseCase)->ejecutar($periodo->getId());
        }

        return view('convenios.index', [
            'convenios' => $convenios,
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'periodoSeleccionado' => $periodoSeleccionado,
        ]);        
    }

    public function create()
    {        
        return view('convenios.create', [
            'convenio' => new Convenio(),
        ]);
    }

    public function store(GuardarConvenio $req)
    {
        $convenioDto = $this->hydrateDto($req->validated());
        $response = (new CrearConvenioUseCase)->ejecutar($convenioDto);                
        return redirect()->route('convenios.index')->with('code', $response->code)->with('status', $response->message);        
    }

    public function edit($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido) 
            return redirect()->route('convenios.index')->with('code', "401")->with('status', "parámetro no válido");
        
        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($id);
        if (!$convenio->existe())
            return redirect()->route('convenios.index')->with('code', "200")->with('status', "Convenio no encontrado");

        return view('convenios.edit', [
            'convenio'=> $convenio,
            'calendarios' => (new ListarCalendariosUseCase)->ejecutar(),
        ]);     
    }

    public function update(GuardarConvenio $req)
    {
        $convenioDto = $this->hydrateDto($req->validated()); 
        $response = (new ActualizarConvenioUseCase)->ejecutar($convenioDto);
        return redirect()->route('convenios.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function destroy($id)
    {
        if (!Validador::parametroId($id)) {
            return redirect()->route('convenios.index')->with('status','parámetro no válido');
        }
        
        $response = (new ConveniosEliminarConvenioUseCase)->ejecutar($id);
        
        return redirect()->route('convenios.index')->with('code', $response->code)->with('status', $response->message);
    }

    public function masInformacion($id) {
        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($id);
        if (!$convenio->existe()) {
            return redirect()->route('convenios.index')->with('code', "200")->with('status', "convenio no encontrado");
        }
        
        return view('convenios.mas_informacion', ['convenio' => $convenio]);
    }

    public function formBeneficiarios($id) {
        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($id);
        if (!$convenio->existe()) {
            return redirect()->route('convenios.index')->with('code', "200")->with('status', "convenio no encontrado");
        }
        
        return view('convenios.beneficiarios', ['convenio' => $convenio]);
    }

    public function cargarBeneficiarios(Request $request) {
        
        $archivo = $request->file('archivo');
        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($request['convenioId']);
        if (!$convenio->existe()) {
            return redirect()->route('convenios.index')->with('code', "200")->with('status', "convenio no encontrado");
        }

        
        if (!$archivo) { 
            return redirect()->route('convenios.index')->with('code', "500")->with('status', "Archivo no vállido");
        }

        (new CargarBeneficiariosConvenioUseCase)->ejecutar($convenio, $archivo);

        return redirect()->route('convenios.index')->with('code', "200")->with('status', "Se ha cargado con éxito los participantes beneficiados.");
    }

    public function facturarConvenio($convenioId) {
        
        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($convenioId);        
        if (!$convenio->existe()) {
            return redirect()->route('convenios.index')->with('code', "404")->with('status', "convenio no encontrado");
        }

        if ($convenio->getDescuento() == 0) {
            return redirect()->route('convenios.index')->with('code', "500")->with('status', "No se puede facturar dado que el porcentaje de escuento es igual a 0");
        }
        
        $convenio = (new FacturarConvenioUseCase)->ejecutar($convenio);

        return view('convenios.mas_informacion', ['convenio' => $convenio])->with('code', "200")->with('status', "Se ha aplicado el descuento a los participantes con éxito.");
    }

    public function listarConveniosPorPeriodo($periodo)
    {   

        if (!Validador::parametroId($periodo))
        {
            return redirect()->route('convenios.index')->with('status','Periodo no válido.');
        }

        return view('convenios.index', [
            'convenios' => (new ListarConveniosPorPeriodoUseCase)->Ejecutar($periodo),
            'periodos' => (new ListarCalendariosUseCase)->ejecutar(),
            'periodoSeleccionado' => $periodo,
        ]);           
    }

    public function hydrateDto($req): ConvenioDto {
        $convenioDto = new ConvenioDto();

        $convenioDto->calendarioId = $req['calendario'];
        $convenioDto->nombre = $req['nombre'];
        $convenioDto->descuento = $req['descuento'];  
        $convenioDto->comentarios = $req['comentarios'];

        $convenioDto->esCooperativa = true;
        if (is_null(request()->esCooperativa)) {
            $convenioDto->esCooperativa = false;
        }

        if (isset(request()->id)) {
            $convenioDto->id = request()->id;
        }             
        return $convenioDto;
    }
}
