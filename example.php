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

// Now we can execute calls to the Teradata DMP API

// 1: A GET request to user/current to get the current user's information
try {
    $service = 'user/current';
    $request = new Request($service);
    $response = $client->get($request);
} catch (ClientException $ex) {
    echo 'An exception has occurred when executing this request: ' . $ex->getMessage();
}

// 2: A POST request to create a new data collection pixel
$pixelId = -1; // Store the pixelId for further usage in PUT/DELETE examples
try {
    $service = 'tracking/beacon';
    $request = new Request($service);
    $request->setParameter('name', 'SDK-created Pixel');
    $request->setParameter('type', 'pixel');
    $response = $client->post($request);

    // Store the id of the created pixel
    $pixelId = $response->get('beacon')['id'];

    echo 'Created a new pixel with id [' . $pixelId . '] and name: [' . $response->get('beacon')['name'] . ']' . PHP_EOL;
} catch (ClientException $ex) {
    echo 'An exception has occurred when executing this request: ' . $ex->getMessage();
}

// 3: A PUT request to change the name of the previously created data collection pixel
try {
    $service = 'tracking/beacon';
    $request = new Request($service);
    $request->setParameter('id', $pixelId);
    $request->setParameter('name', 'SDK-created Pixel has had it\'s name changed');
    $response = $client->put($request);

    echo 'Changed the name of the pixel with id [' . $pixelId . '] to: [' . $response->get('beacon')['name'] . ']' . PHP_EOL;
} catch (ClientException $ex) {
    echo 'An exception has occurred when executing this request: ' . $ex->getMessage();
}

// 4: A DELETE request to delete the previously created data collection pixel
try {
    $service = 'tracking/beacon';
    $request = new Request($service);
    $request->setParameter('id', $pixelId);
    $response = $client->delete($request);

    echo 'Did we delete the previously created data collection pixel (true/false)? --> ' . (bool)$response->get('deleted') . PHP_EOL;
} catch (ClientException $ex) {
    echo 'An exception has occurred when executing this request: ' . $ex->getMessage();
}