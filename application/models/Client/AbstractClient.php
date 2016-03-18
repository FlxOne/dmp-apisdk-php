<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:31
 */
namespace client;
require 'vendor/autoload.php';
require 'IClient.php';


use config\IConfig;
use exception\ClientException;
use GuzzleHttp;
use Logger;
use request\Request;

abstract class AbstractClient implements IClient
{

    protected $client;
    protected $config;
    protected $authToken = null;
    protected $csrfToken = null;
    private static $logger;

    public function __construct(IConfig $config) {
        $this->logger = Logger::getLogger('main');
        $this->client = new GuzzleHttp\Client();
        $this->config = $config;
    }

    protected function authenticate() {
        $req = new Request('auth');
        $req->setParameter('username', $this->config->getUsername());
        $req->setParameter('password', $this->config->getPassword());
        $resp = $this->post($req);
        // @todo: check if $response is null
        if ($resp === null) {
            return false;
        }

        $this->authToken = $resp->get('token');
        $this->csrfToken = $resp->getCsrfToken();
        return true;
    }

    protected function execute($req) {
        $this->logger->info(sprintf('Executing %s request to %s', $req->getMethod(), $req->getUri()));
        if (strpos(strtolower($req->getUri()), 'auth') === false) {
            if (empty($this->authToken) || empty($this->csrfToken)) {
                if (!$this->authenticate()) {
                    throw new ClientException("Failed to authenticate");
                }
            }
        }

        $req->withAddedHeader('X-Auth', $this->authToken);
        $req->withAddedHeader('X-CSRF', $this->csrfToken);
        $req->withHeader('Content-Type', 'application/json');

        $response = null;
        $resp = null;

        for ($i = 0; $i < $this->config->getMaxRetries(); $i++) {
            try {
                $resp = $this->client->send($req);
                $statusCode = $resp->getStatusCode();
                if ($statusCode === 401) {
                    $this->authenticate();
                    continue;
                }

                $response = new Reponse($resp->getBody());
                if ($response->getStatus() === ResponseStatus . OK) {
                    // Stop retrying
                    break;
                }
            } catch (Exception $ex) {
                sleep((1000 * $i * $i) + rand(100));
            }
        }
        return $response;

    }

    function get($request) {
        try {
            $req = new GuzzleHttp\Psr7\Request(
                'GET',
                $this->config->getEndpoint() . '/' . $request->getService(),
                array(
                    'query' => $request->getParameters()
                ));
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    function put($request) {
        try {
            $req = $this->client->put();
            $req->setBody($request->getParameters());
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    function delete($request) {
        try {
            $req = $this->client->delete();
            $req->setBody($request->getParameters());
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    function post($request) {
        try {
            $req = new GuzzleHttp\Psr7\Request(
                'POST',
                $this->config->getEndpoint() . '/' . $request->getService(),
                array(
                    'json' => $request->getParameters()
                ));
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }
}