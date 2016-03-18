<?php
require 'vendor/autoload.php';
require 'application/models/Client/Client.php';
require 'application/models/Config/Config.php';
require 'application/models/Request/Request.php';
use client\Client;
use config\Config;
use request\Request;


$config = Config::getDefault();
$client = new Client($config);
$client->get(new Request('user/current'));