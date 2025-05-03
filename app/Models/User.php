<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;
use Src\domain\repositories\UsuarioRepository;
use Src\domain\Usuario;

class User extends Authenticatable implements UsuarioRepository
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'estado',
        'orientador_id',
        'puede_cargar_firmas',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'estado' => 'string',
    ];

    public function esAdmin(): bool
    {
        return $this->role == 'Admin';
    }

    public function esSuperAdmin(): bool
    {
        return $this->role == 'superAdmin';
    } 
    
    public function esOrientador(): bool
    {
        return strtolower($this->role) == 'orientador';
    } 
    
    public function estaActivo(): bool 
    {
        return $this->estado == 'Activo';
    }

    public function puedeCargarFirma(): bool {
        return $this->puede_cargar_firmas;
    }

    public function cumpleFuncionesComoOrientador(): bool {
        return !is_null($this->orientador_id);
    }

    public function puedeGestionarFirmas(): bool {
        return $this->puede_cargar_firmas;
    }

    public static function BuscarPorId(int $id=0): Usuario
    {
        $usuario = new Usuario();

        $registro = User::find($id);
        if ($registro)
        {
            $usuario->setId($registro->id);
            $usuario->setNombre($registro->name);
            $usuario->setEmail($registro->email);
            $usuario->setRole($registro->role);
            $usuario->setEstado($registro->estado);
            $usuario->setPassword($registro->password);
            $usuario->setFechaCreacion($registro->created_at);
            $usuario->setPuedeCargarFirmas($registro->puede_cargar_firmas);

            if (!is_null($registro->orientador_id)) {
                $usuario->setOrientadorID($registro->orientador_id);
            }
        }

        return $usuario;
    }

    public static function BuscarPorEmail(string $email): Usuario
    {
        $usuario = new Usuario();

        $registro = User::where("email", $email)->first();
        if ($registro)
        {
            $usuario->setId($registro->id);
            $usuario->setNombre($registro->name);
            $usuario->setEmail($registro->email);
            $usuario->setRole($registro->role);
            $usuario->setFechaCreacion($registro->created_at);  
            $usuario->setPuedeCargarFirmas($registro->puede_cargar_firmas);  

            if (!is_null($registro->orientador_id)) {
                $usuario->setOrientadorID($registro->orientador_id);
            }                              
        }

        return $usuario;
    }

    public function crearUsuario(Usuario $usuario): bool
    {
        try 
        {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $orientadorID = null;
            if ($usuario->getOrientadorID() > 0) {
                $orientadorID = $usuario->getOrientadorID();
            }
                    
            $user = User::create([
                'name' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'estado' => $usuario->getEstado(),
                'password' => Hash::make($usuario->getPassword()),
                'role' => $usuario->getRole(),
                'orientador_id' => $orientadorID,
                'puede_cargar_firmas' => $usuario->puedeCargarFirmas(),
                
            ]);

        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }

        if ($user)
            return true;

        return false;   
    }

    public function actualizarUsuario(Usuario $usuario): bool
    {        
        try 
        {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");

            $orientadorID = null;
            if ($usuario->getOrientadorID() > 0) {
                $orientadorID = $usuario->getOrientadorID();
            }            

            $usuarioDB = User::find($usuario->getId());
            if ($usuarioDB)
            {                
                $usuarioDB->update([
                    'id' => $usuario->getId(),
                    'name' => $usuario->getNombre(),
                    'email' => $usuario->getEmail(),
                    'estado' => $usuario->getEstado(),
                    'password' => $usuario->getPassword(),
                    'role' => $usuario->getRole(),
                    'orientador_id' => $orientadorID,
                    'puede_cargar_firmas' => $usuario->puedeCargarFirmas(),                    
                ]);
            }
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }

        return true;
    }
}
