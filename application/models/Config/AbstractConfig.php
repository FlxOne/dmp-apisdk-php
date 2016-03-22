<?php
namespace config;
require 'IConfig.php';

abstract class AbstractConfig implements IConfig
{
    /** @var String Default DMP API endpoint */
    protected $endpoint;

    /** @var String given DMP username */
    protected $username;

    /** @var String given DMP password */
    protected $password;

    /** @var Integer maximum amount of retries */
    protected $maxRetries;

    /**
     * @return String the endpoint
     */
    public function getEndpoint() {
        return $this->endpoint;
    }

    /**
     * @return String the username
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return String the password
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return int the maximum amount of retries
     */
    public function getMaxRetries() {
        return $this->maxRetries;
    }

    /**
     * Set the current endpoint
     * @param $endpoint
     */
    public function setEndpoint($endpoint) {
        $this->endpoint = $endpoint;
    }

    /**
     * Sets the username and password
     * @param $username
     * @param $password
     */
    public function setCredentials($username, $password) {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * Set the amount of maximum retries
     * @param $n
     */
    public function setMaxRetries($n) {
        $this->maxRetries = $n;
    }

    /**
     * Set the password
     * @param $password
     */
    protected function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Set the username
     * @param $username
     */
    protected function setUsername($username) {
        $this->username = $username;
    }

}