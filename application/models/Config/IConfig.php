<?php
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