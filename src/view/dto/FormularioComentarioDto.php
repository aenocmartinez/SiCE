<?php

namespace Src\view\dto;

class FormularioComentarioDto
{
    private string $participante_nombre;
    private string $formulario_numero;
    private string $formulario_estado;
    private string $curso;
    private string $comentario;
    private int $formulario_id;

    public function setNombreParticipante(string $participante_nombre): void 
    {
        $this->participante_nombre = $participante_nombre;
    }

    public function getNombreParticipante(): string
    {
        return $this->participante_nombre;
    }

    public function setNumeroFormulario(string $formulario_numero): void 
    {
        $this->formulario_numero = $formulario_numero;
    }

    public function getNumeroFormulario(): string
    {
        return $this->formulario_numero;
    }

    public function setEstadoFormulario(string $formulario_estado): void
    {
        $this->formulario_estado = $formulario_estado;
    }

    public function getEstadoFormulario(): string 
    {
        return $this->formulario_estado;
    }

    public function setCurso(string $curso): void
    {
        $this->curso = $curso;
    }

    public function getCurso(): string
    {
        return $this->curso;
    }

    public function setComentario(string $comentario): void 
    {
        $this->comentario = $comentario;
    }

    public function getComentario(): string 
    { 
        return $this->comentario;
    }

    public function setFormularioId(int $formulario_id): void 
    {
        $this->formulario_id = $formulario_id;
    }

    public function getFormularioId(): int
    {
        return $this->formulario_id;
    }
}