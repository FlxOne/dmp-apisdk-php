<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:28
 */

namespace response;


interface IResponse
{
    function has($memberName);
    function get($memberName);
    function getCsrfToken();

}