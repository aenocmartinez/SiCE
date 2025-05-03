<?php

namespace Src\domain;

class Firma
{
    private int $id;
    private string $nombreFirmante1;
    private string $cargoFirmante1;
    private string $rutaFirma1;
    private string $nombreFirmante2;
    private string $cargoFirmante2;
    private string $rutaFirma2;

    public function __construct()
    {
        $this->id = 0;
        $this->nombreFirmante1 = '';
        $this->cargoFirmante1 = '';
        $this->rutaFirma1 = '';
        $this->nombreFirmante2 = '';
        $this->cargoFirmante2 = '';
        $this->rutaFirma2 = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNombreFirmante1(): string
    {
        return $this->nombreFirmante1;
    }

    public function setNombreFirmante1(string $nombre): void
    {
        $this->nombreFirmante1 = $nombre;
    }

    public function getCargoFirmante1(): string
    {
        return $this->cargoFirmante1;
    }

    public function setCargoFirmante1(string $cargo): void
    {
        $this->cargoFirmante1 = $cargo;
    }

    public function getRutaFirma1(): string
    {
        return $this->rutaFirma1;
    }

    public function setRutaFirma1(string $ruta): void
    {
        $this->rutaFirma1 = $ruta;
    }

    public function getNombreFirmante2(): string
    {
        return $this->nombreFirmante2;
    }

    public function setNombreFirmante2(string $nombre): void
    {
        $this->nombreFirmante2 = $nombre;
    }

    public function getCargoFirmante2(): string
    {
        return $this->cargoFirmante2;
    }

    public function setCargoFirmante2(string $cargo): void
    {
        $this->cargoFirmante2 = $cargo;
    }

    public function getRutaFirma2(): string
    {
        return $this->rutaFirma2;
    }

    public function setRutaFirma2(string $ruta): void
    {
        $this->rutaFirma2 = $ruta;
    }

    public function existe(): bool {
        return $this->id > 0;
    }
}
