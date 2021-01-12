<?php

namespace TheSoftwareFarm\MicrosoftTeams;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Throwable;

class MicrosoftTeams
{
    /**
     * @param string
     */
    private string $tenantId;

    /**
     * @param string
     */
    private string $clientId;

    /**
     * @param string
     */
    private string $clientSecret;

    /**
     * @param string
     */
    public string $authToken;

    /**
     * The base request url
     * Should probably be https://graph.microsoft.com
     * 
     * @param string
     */
    private string $baseUrl;

    /**
     * The api version (1.0, 2.0 or beta)
     * The package has only been tested with 1.0
     * 
     * @param string
     */
    private string $apiVersion;

    /**
     * The login request url, should be null in most cases
     * 
     * @param string
     */
    private ?string $login;

    /**
     * MicrosoftTeams constructor
     */
    public function __construct()
    {
        $this->tenantId = config('microsoft_teams.tenant_id');
        $this->clientId = config('microsoft_teams.client_id');
        $this->clientSecret = config('microsoft_teams.client_secret');
        $this->baseUrl = config('microsoft_teams.base_url');
        $this->apiVersion = config('microsoft_teams.api_version');
        $this->login = config('microsoft_teams.login');
    }

    /**
     * Returns the login url used to get the authToken.
     *
     * @return string
     */
    private function loginUrl(): string
    {
        if ($this->login) {
            return $this->login;
        }

        return "https://login.microsoftonline.com/$this->tenantId/oauth2/token?api-version=$this->apiVersion";
    }

    /**
     * Returns the baseUrl
     *
     * @return void
     */
    private function endPointBaseUrl(): string
    {
        return "$this->baseUrl/$this->apiVersion";
    }

    /**
     * Authenticates and saves the authToken
     * Returns a response with the authToken and the date when it expires 
     *
     * @return Response
     */
    public function authenticate(): Response
    {
        $client = new Client();

        try {
            $response = $client->post(
                $this->loginUrl(),
                [
                    'form_params' => [
                        'client_secret' => $this->clientSecret,
                        'client_id' => $this->clientId,
                        'grant_type' => 'client_credentials',
                        'scope' => 'api://34ac831b-9ff2-4c69-885a-6476a912334d/full_scope',
                        'resource' => $this->baseUrl
                    ]
                ]
            );

            $data = json_decode($response->getBody()->getContents());

            $this->authToken = $data->access_token;

            return new Response($data, true);
        } catch (Throwable | BadResponseException $exception) {
            return $this->getException($exception);
        }
    }

    /**
     * Returns an array with the users 
     * 
     * https://docs.microsoft.com/en-us/graph/api/user-list?view=graph-rest-1.0&tabs=http
     *
     * @return Response
     */
    public function getUsers(): Response
    {
        $request = new Request(
            'get',
            'users',
            [],
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Creates an user
     *
     * https://docs.microsoft.com/en-us/graph/api/user-post-users?view=graph-rest-1.0&tabs=http
     * 
     * @param array $body Contains data about the new user
     * @return Response
     */
    public function createUser(array $body): Response
    {
        $request = new Request(
            'post',
            'users',
            $body,
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Returns an object with user details
     *
     * https://docs.microsoft.com/en-us/graph/api/user-get?view=graph-rest-1.0&tabs=http
     * 
     * @param string $userPrincipalName Most often the user's email
     * @return Response
     */
    public function getUser(string $userPrincipalName): Response
    {
        $request = new Request(
            'get',
            "users/$userPrincipalName",
            [],
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Returns an object with the licenses of an user
     *
     * https://docs.microsoft.com/en-us/graph/api/user-get?view=graph-rest-1.0&tabs=http
     * 
     * @param string $userPrincipalName Most often the user's email
     * @return Response
     */
    public function getUserLicenses(string $userPrincipalName): Response
    {
        $request = new Request(
            'get',
            "users/$userPrincipalName/licenseDetails",
            [],
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Assign licenses to an user
     *
     * https://docs.microsoft.com/en-us/graph/api/user-get?view=graph-rest-1.0&tabs=http
     * 
     * @param string $userPrincipalName Most often the user's email
     * @param array $body
     * @return Response
     */
    public function assignLicenseToUser(string $userPrincipalName, array $body): Response
    {
        $request = new Request(
            'post',
            "users/$userPrincipalName/assignLicense",
            $body,
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Creates an event
     *
     * https://docs.microsoft.com/en-us/graph/api/user-post-events?view=graph-rest-1.0&tabs=http
     * 
     * @param string $userPrincipalName Most often the user's email
     * @param array $body
     * @return Response
     */
    public function createEvent(string $userPrincipalName, array $body): Response
    {
        $request = new Request(
            'post',
            "users/$userPrincipalName/calendar/events",
            $body,
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Gets the events list
     *
     * https://docs.microsoft.com/en-us/graph/api/user-list-events?view=graph-rest-1.0&tabs=http
     * 
     * @param string $userPrincipalName Most often the user's email
     * @return Response
     */
    public function getEvents(string $userPrincipalName): Response
    {
        $request = new Request(
            'get',
            "users/$userPrincipalName/calendar/events",
            [],
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * Creates an event
     *
     * https://docs.microsoft.com/en-us/graph/api/event-delete?view=graph-rest-1.0&tabs=http
     * 
     * @param string $userPrincipalName Most often the user's email
     * @param string $eventId
     * @return Response
     */
    public function deleteEvent(string $userPrincipalName, string $eventId): Response
    {
        $request = new Request(
            'delete',
            "users/$userPrincipalName/calendar/events/$eventId",
            [],
            $this->authToken,
            $this->endPointBaseUrl()
        );

        return $this->getResponse($request);
    }

    /**
     * @param Request $request
     * @return Response
     */
    private function getResponse(Request $request): Response
    {
        try {
            $response = $request->run();

            $contents = $response->getBody()->getContents();

            return new Response(json_decode($contents), true);
        } catch (Throwable | BadResponseException $exception) {
            return $this->getException($exception);
        }
    }

    /**
     * Undocumented function
     *
     * @param Throwable | BadResponseException $exception
     * @return Response
     */
    private function getException($exception): Response
    {
        if (method_exists($exception, 'getResponse')) {
            $response = $exception->getResponse() ? from_json($exception->getResponse()->getBody()) : null;
        } else {
            $response = $exception;
        }

        return new Response($response, false);
    }
}
