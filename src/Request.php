<?php

namespace TheSoftwareFarm\MicrosoftTeams;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Request
{
    /**
     * @param array
     */
    private array $defaultHeaders;

    /**
     * @param string
     */
    private string $baseUrl;

    /**
     * @param string
     */
    private string $requestType;

    /**
     * @param string
     */
    private string $endPoint;

    /**
     * @param array
     */
    private array $body;

    /**
     * @param Client
     */
    private Client $client;

    /**
     * Request Constructor
     *
     * @param string $requestType
     * @param string $endPoint
     * @param array $body
     * @param string $accessToken
     * @param string $baseUrl
     * @param Client $client
     */
    public function __construct(
        string $requestType,
        string $endPoint,
        array $body,
        string $accessToken,
        string $baseUrl,
        ?Client $client = null
    ) {
        $this->requestType = $requestType;
        $this->endPoint = $endPoint;
        $this->baseUrl = $baseUrl;
        $this->body = $body;

        $this->defaultHeaders = [
            'Host' => $baseUrl,
            'Content-Type' => 'application/json',
            'Authorization' => sprintf('Bearer %s', $accessToken)
        ];

        if (count($this->body)) {
            $this->defaultHeaders['json'] = json_encode($this->body);
        }

        $this->client = $client ?? new Client([
            'headers' => $this->defaultHeaders
        ]);
    }

    /**
     * Executes the request
     *
     * @return ResponseInterface
     */
    public function run(): ResponseInterface
    {
        return $this->client
            ->request(
                $this->requestType,
                sprintf('%s/%s', $this->baseUrl, $this->endPoint),
                [
                    'body' => json_encode($this->body)
                ]
            );
    }
}
