<?php
namespace request;

interface IRequest
{
    function setParameter($key, $value);

    function setParameters(array $parameters);

    function getParameters();

    function getService();
}