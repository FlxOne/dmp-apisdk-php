<?php
require 'vendor/autoload.php';
require 'application/models/Client/Client.php';
require 'application/models/Config/Config.php';
require 'application/models/Request/Request.php';
require 'application/models/Response/Response.php';

use client\Client;
use config\Config;
use exception\ClientException;
use request\Request;

// Create the config
$config = Config::getDefault();

// Set your API credentials
$config->setCredentials('MY_USERNAME', 'MY_PASSWORD');

// Create the SDK Client
$client = new Client($config);

// Execute a GET request to user/current
try {
    $client->get(new Request('user/current'));
} catch (ClientException $ex) {
    echo 'An exception has occurred when executing this request: ' . $ex->getMessage();
}
