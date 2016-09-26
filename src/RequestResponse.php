<?php

namespace Whsuite\Http;

/**
* HTTP RequestResponse
*
* HTTP Response from a Request
*
* @package  WHSuite-HTTP
* @author  WHSuite Dev Team <info@whsuite.com>
* @copyright  Copyright (c) 2013, Turn 24 Ltd.
* @license http://whsuite.com/license/ The WHSuite License Agreement
* @link http://whsuite.com
* @since  Version 1.0.0
*/
class RequestResponse
{
    /**
     * response from the Guzzle Request
     */
    protected $response;

    /**
     * setup the request response
     *
     * @param   Object  Response from Guzzle Request
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * check whether the request was successful
     *
     * @return  bool
     */
    public function isSuccessful()
    {
        return $this->response->isSuccessful();
    }


    /**
     * get the body from the response
     *
     * @return  mixed
     */
    public function getBody()
    {
        if (strtolower($this->getHeaders('Content-Type')) === 'application/json') {

            return $this->response->json();
        }

        return $this->response->getBody(true);
    }


    /**
     * get the headers
     *
     * @param   string  header key to return
     * @param   mixed   default value
     * @return  array
     */
    public function getHeaders($key = null, $defaultValue = null)
    {
        if (empty($key)) {
            return $this->response->getHeaders();
        }

        if (! empty($key) && $this->response->hasHeader($key)) {
            return $this->response->getHeader($key);
        }

        return $defaultValue;
    }


    /**
     * get the status code
     *
     * @return  int     http status code
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }
}
