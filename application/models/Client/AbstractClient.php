<?php
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

    /** The Guzzle HTTP Client */
    protected $client;

    /** @var IConfig The given Config */
    protected $config;

    /** @var The Authentication Token */
    protected $authToken = null;

    /** @var The CSRF Token */
    protected $csrfToken = null;

    /** @var Logger The Log4PHP Logger */
    private static $logger;

    /**
     * Creates the client from a given Config. Instantiates the logger for logging purposes.
     * @param IConfig $config
     */
    public function __construct(IConfig $config) {
        $this->logger = Logger::getLogger('main');
        $this->client = new GuzzleHttp\Client();
        $this->config = $config;
    }

    /**
     * Execute a POST request to authenticate the user. Returns true if authentication was successful.
     * @return bool
     * @throws ClientException
     */
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

    /**
     * Execute the Guzzle HTTP Request with exponential backoff.
     * Authenticate if not authenticated. Adds the required CSRF and Auth tokens in the headers.
     * @param $req
     * @return null|Response
     * @throws ClientException
     */
    protected function execute($req) {
        $this->logger->info(sprintf('Executing %s request to %s', $req->getMethod(), $req->getUri()));
        if (strpos(strtolower($req->getUri()), 'auth') === false) {
            if (empty($this->authToken) || empty($this->csrfToken)) {
                if (!$this->authenticate()) {
                    throw new ClientException(new Exception('Failed to authenticate'));
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

    /**
     * Create a Guzzle HTTP GET Request and execute it. Parameters are set as URL parameters.
     * @param $request
     * @return null|Response
     * @throws ClientException
     */
    function get($request) {
        try {
            $uri = $this->getURIForRequest($request);
            $req = new GuzzleHttp\Psr7\Request(
                'GET',
                $uri
            );
            return $this->execute($req);
        } catch (Exception $ex) {
            throw new ClientException($ex);
        }
    }

    /**
     * Create a Guzzle HTTP PUT Request and execute it. Parameters are set as url-form-encoded body parameters.
     * @param $request
     * @return null|Response
     * @throws ClientException
     */
    function put($request) {
        try {
            $req = new GuzzleHttp\Psr7\Request(
                'PUT',
                $this->config->getEndpoint() . '/' . $request->getService(),
                [],
                http_build_query($request->getParameters(), null, '&')
            );
            return $this->execute($req);
        } catch (Exception $ex) {
            throw new ClientException($ex);
        }
    }

    /**
     * Create a Guzzle HTTP DELETE Request and execute it. Parameters are set as URL parameters.
     * @param $request
     * @return null|Response
     * @throws ClientException
     */
    function delete($request) {
        try {
            $uri = $this->getURIForRequest($request);
            $req = new GuzzleHttp\Psr7\Request(
                'DELETE',
                $uri
            );
            return $this->execute($req);
        } catch (Exception $ex) {
            throw new ClientException($ex);
        }
    }

    /**
     * Create a Guzzle HTTP POST Request and execute it. Parameters are set as url-form-encoded body parameters.
     * @param $request
     * @return null|Response
     * @throws ClientException
     */
    function post($request) {
        try {
            $req = new GuzzleHttp\Psr7\Request(
                'POST',
                $this->config->getEndpoint() . '/' . $request->getService(),
                [],
                http_build_query($request->getParameters(), null, '&')
            );
            return $this->execute($req);
        } catch (Exception $ex) {
            throw new ClientException($ex);
        }
    }

    /**
     * Create a URI with URL parameters from the given Request object
     * @param $request
     * @return GuzzleHttp\Psr7\Uri|\Psr\Http\Message\UriInterface
     */
    protected function getURIForRequest($request) {
        $uri = new GuzzleHttp\Psr7\Uri($this->config->getEndpoint() . '/' . $request->getService());
        foreach ($request->getParameters() as $key => $value) {
            $uri = $uri->withQueryValue($uri, $key, $value);
        }
        return $uri;
    }
}