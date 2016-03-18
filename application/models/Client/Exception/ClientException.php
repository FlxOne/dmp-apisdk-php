<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:29
 */

namespace exception;


class ClientException extends \Exception
{
    public function __construct($exception) {
        parent::construct__(null, null, $exception);
    }

}