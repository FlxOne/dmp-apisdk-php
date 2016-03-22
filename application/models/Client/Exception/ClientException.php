<?php
namespace exception;

class ClientException extends \Exception
{
    public function __construct($exception) {
        parent::construct__(null, null, $exception);
    }
}