<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormularioPublicoConfirmarInscripcion;
use App\Http\Requests\FormularioPublicoGuardarParticipante;
use App\Http\Requests\FormularioPublicoInscripionConsultarExistencia;
use Illuminate\Http\Request;
use Src\domain\Calendario;
use Src\infraestructure\util\ListaDeValor;
use Src\usecase\convenios\BuscarConvenioPorIdUseCase;
use Src\usecase\formularios\ConfirmarInscripcionUseCase;
use Src\usecase\grupos\BuscarGrupoPorIdUseCase;
use Src\usecase\participantes\BuscarParticipantePorDocumentoUseCase;
use Src\usecase\participantes\BuscarParticipantePorIdUseCase;
use Src\usecase\participantes\GuardarParticipanteUseCase;
use Src\view\dto\ConfirmarInscripcionDto;
use Src\view\dto\ParticipanteDto;

class InscripcionPublicaController extends Controller
{
    public function index() {            
        return view('public.inicio');
    }

    public function consultarExistencia(FormularioPublicoInscripionConsultarExistencia $req) {
        $datoFormulario = $req->validated();
        
        $participante = (new BuscarParticipantePorDocumentoUseCase)->ejecutar($datoFormulario['tipoDocumento'], $datoFormulario['documento']);

        $participante->setTipoDocumento($datoFormulario['tipoDocumento']);
        $participante->setDocumento($datoFormulario['documento']);

        request()->session()->put('participante', $participante);

        return redirect()->route('public.formulario-participante');
    }

    public function formularioParticipante(Request $request) {
        $participante = $request->session()->get('participante');

        return view('public.actualizacion_datos', [
            'participante' => $participante,
            'listaEPS' => ListaDeValor::eps(),
            'listaSexo' => ListaDeValor::sexo(),
            'estadosCiviles' => ListaDeValor::estadoCivil()
        ]);
    }

    public function guardarDatosParticipante(FormularioPublicoGuardarParticipante $req) {

        $participante = request()->session()->get('participante');
        $datosFormulario =  $req->validated();

        $participanteDto = $this->hydrateParticipanteDto($datosFormulario);
        $participante->setPrimerNombre($participanteDto->primerNombre);
        $participante->setSegundoNombre($participanteDto->segundoNombre);
        $participante->setPrimerApellido($participanteDto->primerApellido);
        $participante->setSegundoApellido($participanteDto->segundoApellido);

        $response = (new GuardarParticipanteUseCase)->ejecutar($participanteDto);

        if ($response->code == "500") {
            dd("Ha ocurrido un error");
        }
        
        $calendarioVigente = Calendario::Vigente();
        if (!$calendarioVigente->existe()) {
            dd("No hay calendarios vigentes");
        }

        $items = $calendarioVigente->listarGruposParaFormularioInscripcionPublico();
        
        return view('public.seleccion_de_cursos', [
            'items' => $items,
            'participante' => $participante,
        ]);
    }

    public function formularioInscripcion($participanteId, $grupoId) {
        $participante = (new BuscarParticipantePorIdUseCase)->ejecutar($participanteId);
        if (!$participante->existe()) {
            dd("No existe el participante");
        }

        $grupo = (new BuscarGrupoPorIdUseCase)->ejecutar($grupoId);
        if (!$grupo->existe()){ 
            dd("No existe el grupo");
        }

        $convenio = (new BuscarConvenioPorIdUseCase)->ejecutar($participante->getIdBeneficioConvenio());
        
        return view('public.confirmar_inscripcion', [
            'participante' => $participante,
            'grupo' => $grupo,
            'convenio' => $convenio
        ]);
    }

    public function confirmarInscripcion(FormularioPublicoConfirmarInscripcion $req) {
        
        $formularioDto = $this->hydrateConfirmarInscripcionDto( $req->validated() );
        
        $response = (new ConfirmarInscripcionUseCase)->ejecutar($formularioDto);  

        return redirect()->route('public.inicio');
    }

    private function hydrateConfirmarInscripcionDto($datos): ConfirmarInscripcionDto {
        $formularioDto = new ConfirmarInscripcionDto;
        $formularioDto->participanteId = $datos['participanteId'];
        $formularioDto->grupoId = $datos['grupoId'];

        $medioPago = "pagoDatafono";
        if (isset($datos['medioPago'])) {
            $medioPago = $datos['medioPago'];
        }

        $formularioDto->medioPago = $medioPago;
        $formularioDto->convenioId = $datos['convenioId'];
        $formularioDto->costoCurso = $datos['costo_curso'];
        $formularioDto->valorDescuento = $datos['valor_descuento'];
        $formularioDto->totalAPagar = $datos['total_a_pagar'];

        $voucher = 0;
        if (isset($datos['voucher'])) {
            $voucher = $datos['voucher'];
        }
        $formularioDto->voucher = $voucher;

        $valorPago = 0;
        if (isset($datos['valorPago'])) {
            $valorPago = $datos['valorPago'];
        }
        $formularioDto->valorPagoParcial = $valorPago;

        return $formularioDto;
    }
    
    private function hydrateParticipanteDto($dato): ParticipanteDto {
        $participanteDto = new ParticipanteDto;
        $participanteDto->primerNombre = $dato['primerNombre'];
        
        $participanteDto->segundoNombre = '';
        if (!is_null($dato['segundoNombre'])) {
            $participanteDto->segundoNombre = $dato['segundoNombre'];
        }
        $participanteDto->primerApellido = $dato['primerApellido'];

        $participanteDto->segundoApellido = '';
        if (!is_null($dato['segundoApellido'])) {
            $participanteDto->segundoApellido = $dato['segundoApellido'];
        }

        $participanteDto->fechaNacimiento = $dato['fecNacimiento'];
        $participanteDto->tipoDocumento = $dato['tipoDocumento'];
        $participanteDto->documento = $dato['documento'];
        $participanteDto->sexo = $dato['sexo'];
        $participanteDto->estadoCivil = $dato['estadoCivil'];
        $participanteDto->direccion = $dato['direccion'];
        $participanteDto->telefono = $dato['telefono'];
        $participanteDto->email = $dato['email'];
        $participanteDto->eps = $dato['eps'];
        $participanteDto->contactoEmergencia = $dato['contactoEmergencia'];
        $participanteDto->telefonoEmergencia = $dato['telefonoEmergencia'];

        if (isset(request()->id)) {
            $participanteDto->id = request()->id;
        }

        return $participanteDto;
    }    
}
