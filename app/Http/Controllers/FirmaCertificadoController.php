<?php

namespace App\Http\Controllers;

use App\Http\Requests\GuardarFirmasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Src\domain\Firma;
use Src\usecase\certificados\GuardarFirmasUseCase;
use Src\usecase\certificados\ObtenerFirmasUseCase;

class FirmaCertificadoController extends Controller
{
    public function gestionar()
    {        
        $response = (new ObtenerFirmasUseCase)->ejecutar();
        
        /** @var \Src\domain\Firma $firma */
        $firma = $response->data;

        return view('certificados.gestionar-firmas', [
            'firma' => $firma,
        ]);
    }

    public function guardar(GuardarFirmasRequest $request)
    {
        /** @var \Src\domain\Firma $firmaActual */
        $firmaActual = (new ObtenerFirmasUseCase)->ejecutar()->data;

        if ($request->hasFile('ruta_firma1')) 
        {            
            if ($firmaActual->getRutaFirma1() && Storage::disk('public')->exists($firmaActual->getRutaFirma1())) {
                Storage::disk('public')->delete($firmaActual->getRutaFirma1());
            }

            $rutaFirma1 = $request->file('ruta_firma1')->store('firmas', 'public');
        } else {
            $rutaFirma1 = $firmaActual->getRutaFirma1();
        }

        if ($request->hasFile('ruta_firma2')) {
            if ($firmaActual->getRutaFirma2() && Storage::disk('public')->exists($firmaActual->getRutaFirma2())) {
                Storage::disk('public')->delete($firmaActual->getRutaFirma2());
            }

            $rutaFirma2 = $request->file('ruta_firma2')->store('firmas', 'public');
        } else {
            $rutaFirma2 = $firmaActual->getRutaFirma2();
        }

        $firma = new Firma();
        $firma->setId($firmaActual->getId());
        $firma->setNombreFirmante1($request->input('nombre_firmante1'));
        $firma->setCargoFirmante1($request->input('cargo_firmante1'));
        $firma->setRutaFirma1($rutaFirma1);
        $firma->setNombreFirmante2($request->input('nombre_firmante2'));
        $firma->setCargoFirmante2($request->input('cargo_firmante2'));
        $firma->setRutaFirma2($rutaFirma2);

        (new GuardarFirmasUseCase())->ejecutar($firma);

        return redirect()->route('firmas.gestionar')->with('code', '200')->with('status', 'Firmas actualizadas correctamente.');
    }

}
