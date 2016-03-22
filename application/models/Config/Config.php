<?php
namespace config;
require 'AbstractConfig.php';

class Config extends AbstractConfig implements IConfig
{
    /**
     * Creates a default config with the current DMP API endpoint and a maximum of 5 retries on failed requests.
     * @return Config
     */
    public static function getDefault() {
        $conf = new Config();
        $conf->setEndpoint('https://platform.flxone.com/api/v2');
        $conf->setMaxRetries(5);
        return $conf;
    }
}
