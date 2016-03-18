<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:27
 */

namespace client;


interface IClient
{
    function get($request);

    function put($request);

    function delete($request);

    function post($request);
}