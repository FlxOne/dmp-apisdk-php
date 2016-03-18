<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 18/03/16
 * Time: 09:29
 */

namespace response;

abstract class ResponseStatus
{
    const OK = 0;
    const ERROR = 1;
    const NOT_AUTHORIZED = 2;
}