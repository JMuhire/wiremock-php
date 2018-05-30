<?php

namespace WireMock\Client;

class LoggedRequest
{
    private $_url;
    private $_absoluteUrl;
    private $_method;
    private $_clientIp;
    private $_headers;
    private $_cookies;
    private $_body;
    private $_bodyAsBase64;
    private $_browserProxyRequest;
    private $_loggedDate;
    private $_loggedDateString;

    public function __construct(array $requestArray)
    {
        foreach ($requestArray as $key => $value) {
            $property = "_$key";
            $this->{$property} = $value;
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @return string
     */
    public function getAbsoluteUrl()
    {
        return $this->_absoluteUrl;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    /**
     * @return mixed
     */
    public function getClientIp()
    {
        return $this->_clientIp;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * @return mixed
     */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @return mixed
     */
    public function getBodyAsBase64()
    {
        return $this->_bodyAsBase64;
    }

    /**
     * @return boolean
     */
    public function isBrowserProxyRequest()
    {
        return $this->_browserProxyRequest;
    }

    /**
     * @return int
     */
    public function getLoggedDate()
    {
        return $this->_loggedDate;
    }

    /**
     * @return string
     */
    public function getLoggedDateString()
    {
        return $this->_loggedDateString;
    }
}
