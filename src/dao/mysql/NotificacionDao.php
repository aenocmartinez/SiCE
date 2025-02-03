<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Src\domain\repositories\NotificacionRepository;

class NotificacionDao extends Model implements NotificacionRepository
{
    protected $table = 'notificaciones'; 
    protected $fillable = ['calendario_id', 'tipo_mensaje', 'usuario_id', 'fecha']; 

    /**
     * Crear una notificación en la base de datos.
     *
     * @param int $calendarioId
     * @param string $tipoMensaje
     * @return bool|int Devuelve el ID de la notificación creada o false si falla
     */
    public static function CrearNotificacion($calendarioId, $tipoMensaje, $usuarioAutenticado)
    {
        try {                        
            return self::insertGetId([
                'calendario_id' => $calendarioId,
                'tipo_mensaje' => $tipoMensaje,
                'usuario_id' => $usuarioAutenticado,
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            // \Sentry\captureException($e);
            return false;
        }
    }

    public static function NotificacionEnviadaHoy($calendarioId, $tipoMensaje): bool
    {
        try {

            $usuarioId = Auth::id(); 

            if (!$usuarioId) {
                throw new \Exception('Usuario no autenticado.');
            }

            $hoy = now()->toDateString();

            return self::where('calendario_id', $calendarioId)
                ->where('tipo_mensaje', $tipoMensaje)
                ->whereDate('fecha', $hoy)
                ->exists();
        } catch (\Exception $e) {
            // \Sentry\captureException($e);
            Log::info($e->getMessage());
            return false;
        }
    }
    
    
}
