<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:19
 */

namespace config;


interface IConfig
{
    function getEndpoint();
    function getUsername();
    function getPassword();
    function getMaxRetries();
    function setEndpoint($endpoint);
    function setCredentials($user, $password);
    function setMaxRetries($n);
}