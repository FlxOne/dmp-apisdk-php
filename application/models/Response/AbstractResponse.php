<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:52
 */

namespace response;


abstract class AbstractResponse implements IResponse
{
    private $jsonOuterResponseObject;

    public function __construct(String $json) {
        $this->jsonOuterResponseObject = json_decode($json, true);
    }

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

    protected function getResponseObject() {
        return $this->jsonOuterResponseObject['response'];
    }

    function has(String $memberName) {
        return isset($this->getResponseObject()[$memberName]);
    }

    function get(String $memberName) {
        if ($this->has($memberName)) {
            return $this->getResponseObject()[$memberName];
        }
        return null;
    }

    function getCsrfToken() {
        if ($this->has('csrf')) {
            return $this->get('csrf');
        }
        return null;
    }
}