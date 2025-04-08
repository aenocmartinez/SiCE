<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarProfile;
use App\Http\Requests\ActualizarUsuario;
use App\Http\Requests\CrearUsuario;
use Src\infraestructure\util\ListaDeValor;
use Src\infraestructure\util\Validador;
use Src\usecase\orientadores\ListarOrientadoresUseCase;
use Src\usecase\usuarios\ActualizarProfileUseCase;
use Src\usecase\usuarios\ActualizarUsuarioUseCase;
use Src\usecase\usuarios\BuscarUsuarioPorIdUseCase;
use Src\usecase\usuarios\CrearUsuarioUseCase;
use Src\usecase\usuarios\ListarUsuariosUseCase;
use Src\view\dto\UsuarioDto;

class UserController extends Controller
{
    
    public function index()
    {
        return view('usuarios.index', [
            'usuarios' => (new ListarUsuariosUseCase)->Ejecutar(),
        ]);
    }

    public function create()
    {
        $orientadores = (new ListarOrientadoresUseCase)->ejecutar();
        return view('usuarios.create', [
            'roles' => ListaDeValor::roles(),
            'usuario' => new UsuarioDto,
            'orientadores' => $orientadores,
        ]);
    }

    public function store(CrearUsuario $req)
    {
        $data = $req->validated();                
        $data = (object)$data;        

        $estado = 'Activo';
        if (!isset($data->estado)) {
            $estado = 'Inactivo';
        }

        $usuarioDto = new UsuarioDto();
        $usuarioDto->setNombre($data->nombre);
        $usuarioDto->setPassword($data->password);
        $usuarioDto->setEmail($data->email);
        $usuarioDto->setRole($data->role);
        $usuarioDto->setEstado($estado);

        if (isset($data->puede_cargar_firmas)) {
            $usuarioDto->setPuedeCargarFirmas($data->puede_cargar_firmas);
        }

        if (!is_null($data->orientador_id))
        {
            $usuarioDto->setOrientadorID($data->orientador_id);
        }        

        $response = (new CrearUsuarioUseCase)->Ejecutar($usuarioDto);

        return redirect()->route('users.index')->with('code', $response->code)->with('status', $response->message); 
    }

    public function edit($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido)
        {
            return redirect()->route('users.index')->with('code', 500)->with('status', 'Parámetro no válido');
        }        

        $usuario = (new BuscarUsuarioPorIdUseCase)->Ejecutar($id);

        if (!$usuario->Existe())
        {
            return redirect()->route('users.index')->with('code', 500)->with('status', 'El usuario no existe.');
        }

        $orientadores = (new ListarOrientadoresUseCase)->ejecutar();

        return view('usuarios.edit', [
            'roles' => ListaDeValor::roles(),
            'usuario' => $usuario,
            'orientadores' => $orientadores,
        ]);
    }

    public function update(ActualizarUsuario $req)
    {
        $data = $req->validated();  
        $data = (object)$data;        

        $estado = 'Activo';
        if (!isset($data->estado)) {
            $estado = 'Inactivo';
        }

        $password = "";
        if (isset($data->password))
        {
            $password = $data->password;
        }

        $usuarioDto = new UsuarioDto();
        $usuarioDto->setNombre($data->nombre);
        $usuarioDto->setPassword($password);
        $usuarioDto->setEmail($data->email);
        $usuarioDto->setRole($data->role);
        $usuarioDto->setEstado($estado);
        $usuarioDto->setId($data->id);

        if (isset($data->puede_cargar_firmas)) {
            $usuarioDto->setPuedeCargarFirmas($data->puede_cargar_firmas);
        }

        if (!is_null($data->orientador_id))
        {
            $usuarioDto->setOrientadorID($data->orientador_id);
        }

        $response = (new ActualizarUsuarioUseCase)->Ejecutar($usuarioDto);

        return redirect()->route('users.index')->with('code', $response->code)->with('status', $response->message); 
    }

    public function profile($id)
    {
        $esValido = Validador::parametroId($id);
        if (!$esValido)
        {
            return redirect()->route('users.index')->with('code', 500)->with('status', 'Parámetro no válido');
        }        

        $usuario = (new BuscarUsuarioPorIdUseCase)->Ejecutar($id);

        if (!$usuario->Existe())
        {
            return redirect()->route('users.index')->with('code', 500)->with('status', 'El usuario no existe.');
        }

        return view('usuarios.profile', [
            'usuario' => $usuario,
        ]);
    }

    public function updateProfile(ActualizarProfile $req)
    {
        $data = $req->validated();                
        $data = (object)$data;        

        $password = "";
        if (isset($data->password))
        {
            $password = $data->password;
        }

        $usuarioDto = new UsuarioDto();
        $usuarioDto->setNombre($data->nombre);
        $usuarioDto->setPassword($password);
        $usuarioDto->setEmail($data->email);
        $usuarioDto->setId($data->id);

        $response = (new ActualizarProfileUseCase)->Ejecutar($usuarioDto);

        return redirect()->route('dashboard')->with('code', $response->code)->with('status', $response->message);         
    }
}
