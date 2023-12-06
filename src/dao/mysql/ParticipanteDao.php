<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\Calendario;
use Src\domain\Convenio;
use Src\domain\Curso;
use Src\domain\CursoCalendario;
use Src\domain\FormularioInscripcion;
use Src\domain\Grupo;
use Src\domain\Participante;
use Src\domain\repositories\ParticipanteRepository;

use Sentry\Laravel\Facade as Sentry;

class ParticipanteDao extends Model implements ParticipanteRepository {

    protected $table = 'participantes';
    protected $fillable = ['primer_nombre',
                            'segundo_nombre',
                            'primer_apellido',
                            'segundo_apellido',
                            'fecha_nacimiento',
                            'tipo_documento',
                            'documento',
                            'fecha_expedicion',
                            'sexo',
                            'estado_civil',
                            'direccion',
                            'telefono',
                            'email',
                            'eps',
                            'contacto_emergencia',
                            'telefono_emergencia'
                        ];
    
    public function formulariosInscripcion() {
        return $this->hasMany(FormularioInscripcionDao::class, 'participante_id');
    }

    public function buscarParticipantePorDocumento(string $tipo, string $documento): Participante {
        $participante = new Participante;
        try {

            $participanteDao = ParticipanteDao::where('tipo_documento', $tipo)->where('documento', $documento)->first();
            if ($participanteDao) {
                $participante->setId($participanteDao->id);
                $participante->setPrimerNombre($participanteDao->primer_nombre);
                $participante->setSegundoNombre($participanteDao->segundo_nombre);
                $participante->setPrimerApellido($participanteDao->primer_apellido);
                $participante->setSegundoApellido($participanteDao->segundo_apellido);
                $participante->setFechaNacimiento($participanteDao->fecha_nacimiento);
                $participante->setTipoDocumento($participanteDao->tipo_documento);
                $participante->setDocumento($participanteDao->documento);
                $participante->setSexo($participanteDao->sexo);
                $participante->setEstadoCivil($participanteDao->estado_civil);
                $participante->setDireccion($participanteDao->direccion);
                $participante->setTelefono($participanteDao->telefono);
                $participante->setEmail($participanteDao->email);
                $participante->setEps($participanteDao->eps);
                $participante->setContactoEmergencia($participanteDao->contacto_emergencia);
                $participante->setTelefonoEmergencia($participanteDao->telefono_emergencia);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $participante;
    }

    public function crearParticipante(Participante $participante): bool {
        $exito = false;
        try {

            ParticipanteDao::create([
                'primer_nombre' => $participante->getPrimerNombre(),
                'segundo_nombre' => $participante->getSegundoNombre(),
                'primer_apellido' => $participante->getPrimerApellido(),
                'segundo_apellido' => $participante->getSegundoApellido(),
                'fecha_nacimiento' => $participante->getFechaNacimiento(),
                'tipo_documento' => $participante->getTipoDocumento(),
                'documento' => $participante->getDocumento(),
                'sexo' => $participante->getSexo(),
                'estado_civil' => $participante->getEstadoCivil(),
                'direccion' => $participante->getDireccion(),
                'telefono' => $participante->getTelefono(),
                'email' => $participante->getEmail(),
                'eps' => $participante->getEps(),
                'contacto_emergencia' => $participante->getContactoEmergencia(),
                'telefono_emergencia' => $participante->getTelefonoEmergencia()
            ]);

            $exito = true;


        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function actualizarParticipante(Participante $participante): bool {
        $exito = false;
        try {

            ParticipanteDao::whereId($participante->getId())
                ->update([
                    'primer_nombre' => $participante->getPrimerNombre(),
                    'segundo_nombre' => $participante->getSegundoNombre(),
                    'primer_apellido' => $participante->getPrimerApellido(),
                    'segundo_apellido' => $participante->getSegundoApellido(),
                    'fecha_nacimiento' => $participante->getFechaNacimiento(),
                    'tipo_documento' => $participante->getTipoDocumento(),
                    'documento' => $participante->getDocumento(),
                    'sexo' => $participante->getSexo(),
                    'estado_civil' => $participante->getEstadoCivil(),
                    'direccion' => $participante->getDireccion(),
                    'telefono' => $participante->getTelefono(),
                    'email' => $participante->getEmail(),
                    'eps' => $participante->getEps(),
                    'contacto_emergencia' => $participante->getContactoEmergencia(),
                    'telefono_emergencia' => $participante->getTelefonoEmergencia()
                ]);

            $exito = true;


        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $exito;
    }

    public function buscarParticipantePorId(int $participanteId): Participante {
        $participante = new Participante;
        try {

            $participanteDao = ParticipanteDao::find($participanteId);
            if ($participanteDao) {
                $participante->setId($participanteDao->id);
                $participante->setPrimerNombre($participanteDao->primer_nombre);
                $participante->setSegundoNombre($participanteDao->segundo_nombre);
                $participante->setPrimerApellido($participanteDao->primer_apellido);
                $participante->setSegundoApellido($participanteDao->segundo_apellido);
                $participante->setFechaNacimiento($participanteDao->fecha_nacimiento);
                $participante->setTipoDocumento($participanteDao->tipo_documento);
                $participante->setDocumento($participanteDao->documento);
                $participante->setSexo($participanteDao->sexo);
                $participante->setEstadoCivil($participanteDao->estado_civil);
                $participante->setDireccion($participanteDao->direccion);
                $participante->setTelefono($participanteDao->telefono);
                $participante->setEmail($participanteDao->email);
                $participante->setEps($participanteDao->eps);
                $participante->setContactoEmergencia($participanteDao->contacto_emergencia);
                $participante->setTelefonoEmergencia($participanteDao->telefono_emergencia);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }

        return $participante;          
    }

    public function listarParticipantes(): array {
        $participantes = array();
        try {

            $resultados = ParticipanteDao::select('id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 'segundo_apellido', 'tipo_documento', 'documento', 'sexo', 'telefono', 'email')
                            ->orderBy('primer_nombre')
                            ->get();
            
            foreach($resultados as $resultado) {           
                $participante = new Participante();
                $participante->setId($resultado->id);
                $participante->setPrimerNombre($resultado->primer_nombre);
                $participante->setSegundoNombre($resultado->segundo_nombre);
                $participante->setPrimerApellido($resultado->primer_apellido);
                $participante->setSegundoApellido($resultado->segundo_apellido);
                // $participante->setFechaNacimiento($resultado->fecha_nacimiento);
                $participante->setTipoDocumento($resultado->tipo_documento);
                $participante->setDocumento($resultado->documento);
                $participante->setSexo($resultado->sexo);
                // $participante->setEstadoCivil($resultado->estado_civil);
                // $participante->setDireccion($resultado->direccion);
                $participante->setTelefono($resultado->telefono);
                $participante->setEmail($resultado->email);
                // $participante->setEps($resultado->eps);
                // $participante->setContactoEmergencia($resultado->contacto_emergencia);
                // $participante->setTelefonoEmergencia($resultado->telefono_emergencia);

                array_push($participantes, $participante);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }        

        return $participantes;
    }

    public function buscadorParticipantes(string $criterio): array {
        $participantes = array();
        try {
            $resultados = ParticipanteDao::select('id','primer_nombre','segundo_nombre','primer_apellido',
                'segundo_apellido','tipo_documento','documento','sexo','telefono','email'
                )
                ->where(function ($query) use ($criterio) {
                $query->where('primer_nombre', 'like', '%' . $criterio . '%')
                    ->orWhere('segundo_nombre', 'like', '%' . $criterio . '%')
                    ->orWhere('primer_apellido', 'like', '%' . $criterio . '%')
                    ->orWhere('segundo_apellido', 'like', '%' . $criterio . '%')
                    ->orWhere('documento', 'like', '%' . $criterio . '%')
                    ->orWhere('telefono', 'like', '%' . $criterio . '%')
                    ->orWhere('email', 'like', '%' . $criterio . '%');
            })->get();     

            foreach($resultados as $resultado) {           
                $participante = new Participante();
                $participante->setId($resultado->id);
                $participante->setPrimerNombre($resultado->primer_nombre);
                $participante->setSegundoNombre($resultado->segundo_nombre);
                $participante->setPrimerApellido($resultado->primer_apellido);
                $participante->setSegundoApellido($resultado->segundo_apellido);
                $participante->setTipoDocumento($resultado->tipo_documento);
                $participante->setDocumento($resultado->documento);
                $participante->setSexo($resultado->sexo);
                $participante->setTelefono($resultado->telefono);
                $participante->setEmail($resultado->email);

                array_push($participantes, $participante);
            }

        } catch (Exception $e) {
            Sentry::captureException($e);
        }
        return $participantes;
    }

    public function listarFormulariosDeInscripcionParticipante(int $participanteId): array {
        $formularios = array();

        try {
            
            $resultados = FormularioInscripcionDao::select(
                    'formulario_inscripcion.id',
                    'formulario_inscripcion.numero_formulario',
                    'formulario_inscripcion.estado',
                    'formulario_inscripcion.total_a_pagar',
                    'formulario_inscripcion.created_at',
                    'formulario_inscripcion.voucher',
                    'grupos.id as grupo_id',
                    'grupos.dia',
                    'grupos.jornada',
                    'curso_calendario.modalidad',
                    'calendarios.nombre as nombre_calendario',
                    'cursos.nombre as nombre_curso',
                    'convenios.nombre as nombre_convenio'
                )
                ->join('participantes as p', 'p.id', '=', 'formulario_inscripcion.participante_id')
                ->join('grupos', 'grupos.id', '=', 'formulario_inscripcion.grupo_id')
                ->join('calendarios', 'calendarios.id', '=', 'grupos.calendario_id')
                ->join('curso_calendario', 'curso_calendario.id', '=', 'grupos.curso_calendario_id')
                ->join('cursos', 'cursos.id', '=', 'curso_calendario.curso_id')
                ->leftJoin('convenios', 'convenios.id', '=', 'formulario_inscripcion.convenio_id')
                ->where('p.id', $participanteId)
                ->orderByDesc('formulario_inscripcion.id')
                ->get();

                foreach($resultados as $resultado) {
                    $formulario = new FormularioInscripcion();

                    $formulario->setId($resultado->id);
                    $formulario->setNumero($resultado->numero_formulario);
                    $formulario->setEstado($resultado->estado);
                    $formulario->setTotalAPagar($resultado->total_a_pagar);
                    $formulario->setFechaCreacion($resultado->created_at);
                    
                    if (!is_null($resultado->voucher)) {
                        $formulario->setVoucher($resultado->voucher);
                    }

                    $nombreConvenio = "";
                    if (!is_null($resultado->nombre_convenio)) {
                        $nombreConvenio = $resultado->nombre_convenio;
                    }
                    $formulario->setConvenio(new Convenio($nombreConvenio));

                    $grupo = new Grupo();
                    $grupo->setDia($resultado->dia);
                    $grupo->setJornada($resultado->jornada);
                    $grupo->setId($resultado->grupo_id);

                        $calendario = new Calendario();
                        $calendario->setNombre($resultado->nombre_calendario);

                        $curso = new Curso();
                        $curso->setNombre($resultado->nombre_curso);

                        $cursoCalendario = new CursoCalendario($calendario, $curso);
                        $cursoCalendario->setModalidad($resultado->modalidad);

                    $grupo->setCursoCalendario($cursoCalendario);

                    $formulario->setGrupo($grupo);

                    array_push($formularios, $formulario);

                }
            
        } catch(Exception $e) {
            Sentry::captureException($e);
        }

        return $formularios;
    }

    public function eliminarParticipante(int $participanteId): bool {
        $exito = true;
        try {

            $participante = ParticipanteDao::find($participanteId);
            if ($participante) {
                $participante->delete();
            }

        } catch (Exception $e) {
            $exito = false;
            Sentry::captureException($e);
        }
        return $exito;
    }
}