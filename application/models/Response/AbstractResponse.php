<?php
namespace response;
require 'IResponse.php';
require 'ResponseStatus.php';

abstract class AbstractResponse implements IResponse
{
    /** @var array the response's array (decoded JSON into associative array) */
    private $jsonOuterResponseObject;

    /**
     * Create a response from a given JSON string
     * @param $json
     */
    public function __construct($json) {
        $this->jsonOuterResponseObject = json_decode($json, true);
    }

    /**
     * Returns the status of the response
     * @return int
     */
    public function getStatus() {
        try {
            if ($this->getResponseObject()['status'] === 'OK') {
                return ResponseStatus::OK;
            }
        } catch (Exception $ex) {
            // Do nothing
        }
        return ResponseStatus::ERROR;
    }

    /**
     * Get the "response" key from the response
     * @return array
     */
    protected function getResponseObject() {
        return $this->jsonOuterResponseObject['response'];
    }

    /**
     * Check if $memberName is present in the response object
     * @param $memberName
     * @return bool true - present, false - not present
     */
    function has($memberName) {
        return isset($this->getResponseObject()[$memberName]);
    }

    /**
     * Gets the $memberName key from the response object
     * @param $memberName
     * @return mixed result - when found, null - when not found
     */
    function get($memberName) {
        if ($this->has($memberName)) {
            return $this->getResponseObject()[$memberName];
        }
        return null;
    }

    /**
     * Gets the CSRF token from the response
     * @return mixed|null
     */
    function getCsrfToken() {
        if ($this->has('csrf')) {
            return $this->get('csrf');
        }
        return null;
    }
}