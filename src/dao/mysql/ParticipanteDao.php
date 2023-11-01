<?php

namespace Src\dao\mysql;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Src\domain\Participante;
use Src\domain\repositories\ParticipanteRepository;

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
                // $participante->setFechaExpedicion($participanteDao->fecha_expedicion);
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
            dd($e->getMessage());
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
            dd($e->getMessage());
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
            dd($e->getMessage());
        }

        return $exito;
    }

}