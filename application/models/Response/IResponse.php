<?php
namespace response;

interface IResponse
{
    function has($memberName);

    function get($memberName);

    function getCsrfToken();
}