<?php
namespace client;

interface IClient
{
    function get($request);

    function put($request);

    function delete($request);

    function post($request);
}