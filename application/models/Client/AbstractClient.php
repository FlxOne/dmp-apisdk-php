<?php
/**
 * Created by IntelliJ IDEA.
 * User: pv186013
 * Date: 17/03/16
 * Time: 16:31
 */
namespace client;
require 'IClient.php';

use config\IConfig;
use exception\ClientException;
use GuzzleHttp;
use Logger;
use request\Request;
use response\Response;
use response\ResponseStatus;

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
        $request = new Request('auth');
        $request->setParameter('username', $this->config->getUsername());
        $request->setParameter('password', $this->config->getPassword());
        $response = $this->post($request);
        // @todo: check if $response is null
        if ($response === null) {
            return false;
        }

        $this->authToken = $response->get('token');
        $this->csrfToken = $response->getCsrfToken();
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


        $req = $req->withAddedHeader('X-Auth', $this->authToken);
        $req = $req->withAddedHeader('X-CSRF', $this->csrfToken);

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

                $response = new Response($resp->getBody()->getContents());
                if ($response->getStatus() === ResponseStatus::OK) {
                    // Stop retrying
                    break;
                }
            } catch (Exception $ex) {
                sleep((1000 * $i * $i) + rand(0, 100));
            }
        }
        return $response;

    }

    function get($request) {
        try {
            $uri = $this->getURIForRequest($request);
            $req = new GuzzleHttp\Psr7\Request(
                'GET',
                $uri
            );
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    function put($request) {
        try {
            $uri = $this->getURIForRequest($request);
            $req = new GuzzleHttp\Psr7\Request(
                'PUT',
                $uri
            );
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    function delete($request) {
        try {
            $uri = $this->getURIForRequest($request);
            $req = new GuzzleHttp\Psr7\Request(
                'DELETE',
                $uri
            );
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    function post($request) {
        try {
            $uri = $this->getURIForRequest($request);
            $req = new GuzzleHttp\Psr7\Request(
                'POST',
                $uri
            );
            return $this->execute($req);
        } catch (Exception $ex) {

        }
    }

    protected function getURIForRequest($request) {
        $uri = new GuzzleHttp\Psr7\Uri($this->config->getEndpoint() . '/' . $request->getService());
        foreach ($request->getParameters() as $key => $value) {
            $uri = $uri->withQueryValue($uri, $key, $value);
        }
        return $uri;
    }
}