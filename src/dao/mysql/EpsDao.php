<?php

namespace Src\dao\mysql;

use Illuminate\Database\Eloquent\Model;
use Sentry\Laravel\Facade as Sentry;
use Src\domain\Eps;
use Src\domain\repositories\EpsRepository;

class EpsDao extends Model implements EpsRepository
{
    protected $table = 'eps';

    protected $fillable = ['nombre'];

    public $timestamps = false;

    /**
     * Buscar una EPS por nombre exacto.
     */
    public function buscar(string $nombre): Eps
    {
        try {
            $eps = self::where('nombre', $nombre)->first();
            return $eps ? new Eps($eps) : null;
        } catch (\Throwable $e) {
            Sentry::captureException($e);
            return new Eps($this);
        }
    }

    /**
     * Listar todas las EPS como array de nombres
     */
    public function listar(): array
    {
        try {
            return self::orderBy('nombre')
                ->pluck('nombre')  // Retorna directamente los nombres
                ->all();           // Convierte la colección en array
        } catch (\Throwable $e) {
            Sentry::captureException($e);
            return [];
        }
    }

    /**
     * Crear una nueva EPS con el nombre proporcionado.
     */
    public function crear(string $nombre): bool
    {
        try {            
            self::create(['nombre' => $nombre]);
            return true;
        } catch (\Throwable $e) {
            $e->getMessage();
            //Sentry::captureException($e);
            return false;
        }
    }
}
