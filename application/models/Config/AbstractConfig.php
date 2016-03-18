<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:18
 */

namespace config;
require 'IConfig.php';


abstract class AbstractConfig implements IConfig
{

    protected $endpoint;
    protected $username;
    protected $password;
    protected $maxRetries;

    public function getEndpoint() {
        return $this->endpoint;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getMaxRetries() {
        return $this->maxRetries;
    }

    public function setEndpoint($endpoint) {
        $this->endpoint = $endpoint;
    }

    public function setCredentials($username, $password) {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    public function setMaxRetries($n) {
        $this->maxRetries = $n;
    }

    protected function setPassword($password) {
        $this->password = $password;
    }

    protected function setUsername($username) {
        $this->username = $username;
    }

}