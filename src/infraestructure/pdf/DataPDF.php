<?php

namespace Src\infraestructure\pdf;

class DataPDF {

    private string $fileName;
    private $data = [];
    private $dataString = "";

    public function __construct($fileName="fileName"){
        $this->fileName = $fileName;
    }

    public function setFileName(string $fileName="fileName"): void {
        $this->fileName = $fileName;
    }

    public function getFileName(): string {
        return $this->fileName;
    }

    public function setData($data=[]): void {
        $this->data = $data;
    }

    public function getData(): array {
        return $this->data;
    }

    public function setDataString(string $dataString): void {
        $this->dataString = $dataString;
    }

    public function getDataString(): string {
        return $this->dataString;
    }
}