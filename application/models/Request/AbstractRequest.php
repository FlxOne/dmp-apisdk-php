<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:46
 */

namespace request;
require 'IRequest.php';

abstract class AbstractRequest implements IRequest
{

    protected $parameters = array();
    protected $service;

    public function __construct($service) {
        $this->service = $service;
    }

    function setParameter($key, $value) {
        $this->parameters[$key] = $value;
    }

    function setParameters(array $parameters) {
        $this->parameters = $parameters;
    }

    function getParameters() {
        return $this->parameters;
    }

    function getService() {
        return $this->service;
    }
}