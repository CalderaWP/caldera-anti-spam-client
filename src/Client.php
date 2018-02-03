<?php


namespace calderawp\AntiSpamClient;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * API Client for Caldera Anti-Spam
 *
 * @package calderawp\AntiSpamClient
 */
class Client
{

    /** @var string */
    const APIVER = '1';
    /** @var string */
    const HEADER = 'X-CS-CAS';
    /** @var string */
    const VALIDKEY = 'allow';
    /** @var string */
    protected $apiUrl;
    /** @var string */
    protected $apiKey;

    /**
     * @var ResponseInterface
     */
    protected $lastResponse;

    /**
     * HTTP client
     *
     * @var \GuzzleHttp\Client|ClientInterface
     */
    protected $httpClient;

    /**
     * Whitelisted routes this client supports
     *
     * @var array
     */
    protected $routes = [
        'content'
    ];

    /**
     * Client constructor.
     * @param string $apiKey API Key
     * @param string $apiUrl API URL
     * @param ClientInterface|null $httpClient Guzzle client. If null, one will be created.
     */
    public function __construct($apiKey, $apiUrl, ClientInterface $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->apiUrl = $apiUrl;
        $this->httpClient = !$httpClient ? $this->createClient() : $httpClient;
    }

    /**
     * Given Content entity, check if is spam against remote API
     *
     * @param Content $content
     * @return bool
     * @throws Exception
     */
    public function checkContent(Content $content)
    {
        $request = $content->toRequest($this);
        try {
            $response = $this->doRequest($request);
            if (200 === $response->getStatusCode()) {
                $body = (array)\GuzzleHttp\json_decode($response->getBody());
                if (!empty($body) && isset($body[self::VALIDKEY])) {
                    return (bool)$body[self::VALIDKEY];
                } elseif (422 === $response->getStatusCode()) {
                    throw new Exception('Invalid', 422);
                }
            }
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }



    /**
     *  Do request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    protected function doRequest(RequestInterface $request)
    {
        $this->lastResponse = $this->httpClient->send($request);
        return $this->lastResponse;
    }


    /**
     * Creates headers
     *
     * @return array
     */
    public function createHeaders()
    {
        return [
            'Content-Type' => 'application/json',
            self::HEADER => $this->apiKey
        ];
    }


    /**
     * Create HTTP client
     *
     * @return \GuzzleHttp\Client
     */
    protected function createClient()
    {
        return new \GuzzleHttp\Client(
            [
                'base_uri' => $this->apiUrl,
                'timeout' => 2.0,
            ]
        );
    }

    /**
     * Create route endpoint
     *
     * @param string $endpoint Endpoint to request
     * @return string
     */
    public function getEndpointUrl($endpoint)
    {
        if ($this->endpointIsValid($endpoint)) {
            return sprintf('%s/api/v%s/%s', $this->apiUrl, self::APIVER, $endpoint);
        }

        return '';
    }

    /**
     * Check if this client has specified endpoint
     *
     * @param string $endpoint Endpoint to request
     * @return bool
     */
    public function endpointIsValid($endpoint)
    {
        return in_array($endpoint, $this->routes);
    }

    /**
     * Get last response's status code
     *
     * @return int
     */
    public function getLastResponseCode()
    {
        return $this->lastResponse->getStatusCode();
    }
}
