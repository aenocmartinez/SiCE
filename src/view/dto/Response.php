<?php

namespace Src\view\dto;

class Response {
    public string $code;
    public string $message;
    public $data = [];

    public function __construct(string $code="", string $message="") {
        $this->code = $code;
        $this->message = $message;
    }
}