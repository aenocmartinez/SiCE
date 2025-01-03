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
    
    public function estaActivo(): bool 
    {
        return $this->estado == 'Activo';
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
        }

        return $usuario;
    }

    public function crearUsuario(Usuario $usuario): bool
    {
        try 
        {
            $idUsuarioSesion = Auth::id();
            DB::statement("SET @usuario_sesion = $idUsuarioSesion");
                    
            $user = User::create([
                'name' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'estado' => $usuario->getEstado(),
                'password' => Hash::make($usuario->getPassword()),
                'role' => $usuario->getRole(),
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
