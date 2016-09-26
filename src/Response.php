<?php

namespace Whsuite\Http;

/**
* HTTP Response
*
* HTTP Response Wrapper for Symfony Responses
*
* @package  WHSuite-HTTP
* @author  WHSuite Dev Team <info@whsuite.com>
* @copyright  Copyright (c) 2013, Turn 24 Ltd.
* @license http://whsuite.com/license/ The WHSuite License Agreement
* @link http://whsuite.com
* @since  Version 1.0.0
*/
class Response
{
    /**
     * Symfony Response Object
     *
     */
    protected $response;

    /**
     * type of response requests
     */
    protected $type;

    /**
     * start new response
     *
     * @param   string  (optional) Type of response
     */
    public function __construct($type = null)
    {
        $this->type = $type;
        if (! empty($type) && class_exists('\Symfony\Component\HttpFoundation\\' . ucfirst($type) . 'Response')) {

            $className = '\Symfony\Component\HttpFoundation\\' . ucfirst($type) . 'Response';
            $this->response = new $className;
        } else {

            $this->response = new \Symfony\Component\HttpFoundation\Response;
        }
    }


    /**
     * send the response to the browser
     *
     */
    public function send()
    {
        $Request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

        $this->response->prepare($Request);

        $this->response->send();
    }


    /**
     * set the content to the response
     *
     * @param   mixed   Date to be sent
     * @return  Response|bool
     */
    public function setContent($content)
    {
        try {
            if ($this->type === 'json') {
                $this->response->setData($content);
            } else {
                $this->response->setContent($content);
            }

            return $this;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * set headers
     *
     * @param   array   Key => Value array of headers
     * @return  Response
     */
    public function setHeaders($headers)
    {
        $this->response->headers->add($headers);

        return $this;
    }

    /**
     * get the headers
     *
     * @param string  $key     The header name
     * @param mixed   $default The default value
     * @param bool    $first   Whether to return the first value or all header values
     *
     * @return string|array The first header value if $first is true, an array of values otherwise
     */
    public function getHeaders($key, $default = null, $first = true)
    {
        return $this->response->headers->get($key, $default, $first);
    }


    /**
     * function overloading to access other functions if needed
     *
     * @param   string  method name we are trying to load
     * @param   array   array of params to pass to the method
     * @retun   mixed   return of the method
     */
    public function __call($name, $params)
    {
        if (method_exists($this->response, $name)) {

            $method_reflection = new \ReflectionMethod($this->response, $name);

            return $method_reflection->invokeArgs($this->response, $params);

        } else {

            throw new \Exception('Fatal Error: Function '.$name.' does not exist!');
        }
    }
}
