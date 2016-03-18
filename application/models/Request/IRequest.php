<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:27
 */

namespace request;


interface IRequest
{
    function setParameter($key, $value);

    function setParameters(array $parameters);

    function getParameters();

    function getService();
}