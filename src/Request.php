<?php

namespace Whsuite\Http;

/**
* HTTP Request
*
* HTTP Request Wrapper for Guzzle Requests
*
* @package  WHSuite-HTTP
* @author  WHSuite Dev Team <info@whsuite.com>
* @copyright  Copyright (c) 2013, Turn 24 Ltd.
* @license http://whsuite.com/license/ The WHSuite License Agreement
* @link http://whsuite.com
* @since  Version 1.0.0
*/
class Request
{
    /**
     * Guzzle Client Object
     */
    protected $request;

    /**
     * url to send request to
     */
    protected $url = null;

    /**
     * auth details
     */
    protected $auth = array();

    /**
     * the request method to use
     */
    protected $method = 'get';

    /**
     * headers
     */
    protected $headers = array();

    /**
     * content body to send
     */
    protected $content = null;

    /**
     * validate ssl
     */
    protected $verify = true;

    /**
     * ssl cert
     */
    protected $cert = null;

    /**
     * SSL Key
     */
    protected $sslKey = null;

    /**
     * Timeout
     */
    protected $timeout = null;

    /**
     * Connect Timeout
     */
    protected $connectTimeout = null;

    /**
     * start new request
     *
     */
    public function __construct()
    {
        $this->request = new \Guzzle\Http\Client;
    }


    /**
     * send the request
     *
     * @return  RequestResponse
     *
     * @throws  RuntimeException
     */
    public function send()
    {
        if (empty($this->url)) {
            throw new \RuntimeException('No URL is set, cannot send request');
        }

        // build the params for the guzzl request
        $params = array(
            $this->url,
            $this->headers
        );

        if (in_array($this->method, array('delete', 'put', 'patch', 'post'))) {
            $params[] = $this->content;
        }

        // build the options array
        $params[] = $this->buildOptions();

        $Request = call_user_func_array(
            array(
                $this->request,
                $this->method
            ),
            $params
        );

        $Request->getCurlOptions()->set(CURLOPT_SSLVERSION, 1);
        return new \Whsuite\Http\RequestResponse($Request->send());
    }


    /**
     * build the options for guzzle
     *
     * @return  array   array of options for guzzle
     */
    public function buildOptions()
    {
        $options = array();

        if (! empty($this->auth)) {

            $options['auth'] = array(
                $this->auth['user'],
                $this->auth['pass'],
                $this->auth['type']
            );
        }

        $options['verify'] = $this->verify;

        if (! empty($this->cert)) {
            $options['cert'] = $this->cert;
        }

        if (! empty($this->sslKey)) {
            $options['ssl_key'] = $this->sslKey;
        }

        if ($this->timeout === 0 || $this->timeout > 0) {
            $options['timeout'] = $this->timeout;
        }

        if ($this->connectTimeout === 0 || $this->connectTimeout > 0) {
            $options['connect_timeout'] = $this->connectTimeout;
        }

        return $options;
    }


    /**
     * set url to request
     *
     * @param   string  url to set
     * @return  Request
     *
     * @throws  \InvalidArgumentException
     */
    public function setUrl($url)
    {
        if (empty($url)) {
            throw new \InvalidArgumentException('A valid URL must be set');
        }

        $this->url = $url;
        return $this;
    }


    /**
     * get a url that's been set
     *
     * @return  string|null
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * set Auth details
     *
     * @param   string  Username
     * @param   string  Password
     * @param   string  type
     * @return  Request
     *
     * @throws  \InvalidArgumentException
     */
    public function setAuth($user, $pass, $type = null)
    {
        if (empty($user)) {
            throw new \InvalidArgumentException('A username must be set');
        }

        if (empty($pass)) {
            throw new \InvalidArgumentException('A password must be set');
        }

        if (empty($type)) {
            $type = 'basic';
        }

        $validAuth = in_array(strtolower($type), array('basic', 'digest', 'ntlm', 'any'));
        if (empty($type) || ! $validAuth) {
            throw new \InvalidArgumentException('A valid Auth type must be selected');
        }

        $this->auth = array(
            'user' => $user,
            'pass' => $pass,
            'type' => $type
        );
    }

    /**
     * get array of auth details set
     *
     * @return  array
     */
    public function getAuth()
    {
        return $this->auth;
    }


    /**
     * set the method to use
     *
     * @param   string  Method to use on request
     * @return  Request
     *
     * @throws \InvalidArgumentException
     */
    public function setMethod($method)
    {
        $methodTypes = array('get', 'head', 'delete', 'post', 'put', 'patch');
        $validMethod = in_array($method, $methodTypes);

        if (empty($method) || ! $validMethod) {
            throw new \InvalidArgumentException('A valid HTTP method must be used. Valid types: ' . implode(', ', $methodTypes));
        }

        $this->method = $method;
        return $this;
    }


    /**
     * get the currently set http method
     *
     * @return  string  http method to use
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * set any headers that need to be sent in the request
     *
     * @param   string|array    array of headers or header key
     * @param   mixed           value
     * @return  Request
     */
    public function setHeaders($key, $value = null)
    {
        if (is_array($key)) {
            $this->headers = $key;
        } else {
            $this->headers[$key] = $value;
        }
        return $this;
    }


    /**
     * get headers
     *
     * @param   string  key to retieve
     * @param   mixed   default value
     * @return  mixed
     */
    public function getHeaders($key = null, $defaultValue = null)
    {
        if (empty($key)) {
            return $this->headers;
        }

        if (! empty($key) && isset($this->headers[$key])) {
            return $this->headers[$key];
        }

        return $defaultValue;
    }


    /**
     * set the content to the request
     *
     * @param   mixed   Data to be sent
     * @return  Request
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }


    /**
     * get the content to the request
     *
     * @return  mixed   Data to be sent
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * set whether to validate ssl
     *
     * @param   bool
     * @return  Request
     */
    public function setVerify($verify)
    {
        $this->verify = $verify;

        return $this;
    }


    /**
     * get the validate ssl status
     *
     * @return  bool
     */
    public function getVerify()
    {
        return $this->verify;
    }


    /**
     * set the ssl cert
     *
     * @param   bool
     * @return  Request
     */
    public function setCert($file, $password = null)
    {
        if (! empty($password)) {
            $this->cert = array($file, $password);
        } else {
            $this->cert = $file;
        }

        return $this;
    }


    /**
     * get the ssl cert info
     *
     * @return  string|array
     */
    public function getCert()
    {
        return $this->cert;
    }


    /**
     * set whether to validate ssl
     *
     * @param   bool
     * @return  Request
     */
    public function setSslKey($file, $password = null)
    {
        if (! empty($password)) {
            $this->sslKey = array($file, $password);
        } else {
            $this->sslKey = $file;
        }

        return $this;
    }


    /**
     * get the validate ssl status
     *
     * @return  string|array
     */
    public function getSslKey()
    {
        return $this->sslKey;
    }


    /**
     * set timeout in seconds
     *
     * @param   int
     * @return  Request
     */
    public function setTimeout($timeout = 0)
    {
        $this->timeout = $timeout;

        return $this;
    }


    /**
     * get the validate ssl status
     *
     * @return  int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }


    /**
     * set connect timeout in seconds
     *
     * @param   int
     * @return  Request
     */
    public function setConnectTimeout($timeout = 0)
    {
        $this->connectTimeout = $timeout;

        return $this;
    }


    /**
     * get the validate ssl status
     *
     * @return  int
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }
}
