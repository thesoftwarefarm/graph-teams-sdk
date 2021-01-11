<?php

namespace TheSoftwareFarm\MicrosoftTeams;

class Response
{
    /**
     * @param bool
     */
    private bool $isSuccessful;

    /**
     * @param object|null
     */
    private ?object $response;

    /**
     * Response constructor
     *
     * @param object|null $response
     * @param bool $isSuccessful
     */
    public function __construct(?object $response, bool $isSuccessful)
    {
        $this->isSuccessful = $isSuccessful;
        $this->response = $response;
    }

    /**
     * Returns if a the request was successfully
     * 
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->isSuccessful;
    }

    /**
     * Returns the request response
     * 
     * @return Object
     */
    public function getResponse()
    {
        return $this->response;
    }
}
