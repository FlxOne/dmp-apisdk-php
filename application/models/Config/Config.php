<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:24
 */

namespace config;
require 'AbstractConfig.php';


class Config extends AbstractConfig implements IConfig
{
    public static function getDefault() {
        $credentials = include('credentials.php');
        $conf = new Config();
        $conf->setCredentials($credentials['username'], $credentials['password']);
        $conf->setEndpoint('https://platform.flxone.com/api/v2');
        $conf->setMaxRetries(5);
        return $conf;
    }
}