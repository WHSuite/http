<?php

namespace Whsuite\Http;

/**
* HTTP
*
* HTTP Wrapper for Guzzle Requests / Symfony Responses
*
* @package  WHSuite-HTTP
* @author  WHSuite Dev Team <info@whsuite.com>
* @copyright  Copyright (c) 2013, Turn 24 Ltd.
* @license http://whsuite.com/license/ The WHSuite License Agreement
* @link http://whsuite.com
* @since  Version 1.0.0
*/
class Http
{
    /**
     * Return an instance of our Response object
     *
     * @param   string  (optional) Type of response
     * @return  Object  Response Object
     */
    public function newResponse($type = null)
    {
        return new \Whsuite\Http\Response($type);
    }

    /**
     * Return an instance of our Request object
     *
     * @return  Object  Request Object
     */
    public function newRequest()
    {
        return new \Whsuite\Http\Request();
    }

    /**
     * send the request / response
     *
     * @param   Response/Request Object
     */
    public function send($object)
    {
        $isRequest = is_a($object, '\Whsuite\Http\Request');
        $isResponse = is_a($object, '\Whsuite\Http\Response');

        if ($isRequest || $isResponse) {
            return $object->send();
        }

        return false;
    }
}
