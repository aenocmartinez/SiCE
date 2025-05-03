<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\domain\Firma;
use Src\domain\repositories\FirmaRepository;
use Sentry\Laravel\Facade as Sentry;

class FirmaDao extends Model implements FirmaRepository
{
    protected $table = 'firmas_certificados';
    protected $fillable = [
        'nombre_firmante1', 
        'cargo_firmante1',
        'ruta_firma1',
        'nombre_firmante2', 
        'cargo_firmante2',
        'ruta_firma2',
    ];

    public static function ObtenerFirmas(): Firma
    {
        $firma = new Firma(); 
    
        try {
            $resultado = FirmaDao::first(); 
            if ($resultado) {
                $firma->setId($resultado->id);
                $firma->setNombreFirmante1($resultado->nombre_firmante1);
                $firma->setCargoFirmante1($resultado->cargo_firmante1);
                $firma->setRutaFirma1($resultado->ruta_firma1);
                $firma->setNombreFirmante2($resultado->nombre_firmante2);
                $firma->setCargoFirmante2($resultado->cargo_firmante2);
                $firma->setRutaFirma2($resultado->ruta_firma2);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            // Sentry::captureException($e);
        }
    
        return $firma;
    }    

    public function GuardarFirma(Firma $firma): bool
    {
        $exito = false;

        try {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $registro = FirmaDao::first();

            if ($registro) {
                $registro->update([
                    'nombre_firmante1' => $firma->getNombreFirmante1(),
                    'cargo_firmante1' => $firma->getCargoFirmante1(),
                    'ruta_firma1' => $firma->getRutaFirma1(),
                    'nombre_firmante2' => $firma->getNombreFirmante2(),
                    'cargo_firmante2' => $firma->getCargoFirmante2(),
                    'ruta_firma2' => $firma->getRutaFirma2(),
                ]);
            } else {
                FirmaDao::create([
                    'nombre_firmante1' => $firma->getNombreFirmante1(),
                    'cargo_firmante1' => $firma->getCargoFirmante1(),
                    'ruta_firma1' => $firma->getRutaFirma1(),
                    'nombre_firmante2' => $firma->getNombreFirmante2(),
                    'cargo_firmante2' => $firma->getCargoFirmante2(),
                    'ruta_firma2' => $firma->getRutaFirma2(),
                ]);
            }

            $exito = true;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            // Sentry::captureException($e);
        }

        return $exito;
    }
}
