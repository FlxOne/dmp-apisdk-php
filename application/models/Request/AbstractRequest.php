<?php
namespace request;
require 'IRequest.php';

abstract class AbstractRequest implements IRequest
{
    /** @var array the parameters for this request */
    protected $parameters = array();

    /** @var String the service to be called */
    protected $service;

    /**
     * Creates a Request directed towards a given service
     * @param $service
     */
    public function __construct($service) {
        $this->service = $service;
    }

    /**
     * Set a parameter with the given key/value
     * @param $key
     * @param $value
     */
    function setParameter($key, $value) {
        $this->parameters[$key] = $value;
    }

    /**
     * Adds the given parameters to the request
     * @param array $parameters
     */
    function setParameters(array $parameters) {
        foreach ($parameters as $key => $value) {
            $this->setParameter($key, $value);
        }
    }

    /**
     * Returns the request's parameters
     * @return array
     */
    function getParameters() {
        return $this->parameters;
    }

    /**
     * Returns the request's service
     * @return String
     */
    function getService() {
        return $this->service;
    }
}