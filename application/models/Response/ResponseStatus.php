<?php
namespace response;

abstract class ResponseStatus
{
    const OK = 0;
    const ERROR = 1;
    const NOT_AUTHORIZED = 2;
}