<?php

namespace Frankie\Request\Path;

use Ds\Map;
use Frankie\Request\RequestInterface;
use Frankie\Request\Server\ServerContainer;

class PathInfo
{
    private string $path;
    private ServerContainer $serverContainer;

    public function __construct(ServerContainer $serverContainer)
    {
        $this->serverContainer = $serverContainer;
        $queryString = '';
        if ($serverContainer->hasServerParameter(RequestInterface::QUERY_STRING_KEY)) {
            $queryString = $serverContainer->getServerParameter(RequestInterface::QUERY_STRING_KEY);
        }
        $uri = '';
        if ($serverContainer->hasServerParameter(RequestInterface::REQUEST_URI_KEY)) {
            $uri = $serverContainer->getServerParameter(RequestInterface::REQUEST_URI_KEY);
        }
        $this->path = trim(str_replace($queryString, '', $uri), ' /?');
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getURIParts(): Map
    {
        return new Map(explode(' / ', trim($this->path, ' / ')));
    }

    public function getURL(): string
    {
        $baseURL = '';
        if ($this->serverContainer->hasServerParameter(RequestInterface::REQUEST_SCHEME_KEY)) {
            $baseURL = $this->serverContainer->getServerParameter(
                    RequestInterface::REQUEST_SCHEME_KEY
                ) . '://';
        }
        if ($this->serverContainer->hasServerParameter(RequestInterface::HTTP_HOST_KEY)) {
            $baseURL .= $this->serverContainer->getServerParameter(RequestInterface::HTTP_HOST_KEY);
        }
        return rtrim($baseURL . '/' . $this->path, '/');
    }

    public function getURLWithQueries(): string
    {
        $baseURL = '';
        if ($this->serverContainer->hasServerParameter(RequestInterface::REQUEST_SCHEME_KEY)) {
            $baseURL = $this->serverContainer->getServerParameter(
                    RequestInterface::REQUEST_SCHEME_KEY
                ) . '://';
        }
        if ($this->serverContainer->hasServerParameter(RequestInterface::HTTP_HOST_KEY)) {
            $baseURL .= $this->serverContainer->getServerParameter(RequestInterface::HTTP_HOST_KEY);
        }
        $uri = '';
        if ($this->serverContainer->hasServerParameter(RequestInterface::REQUEST_URI_KEY)) {
            $uri = $this->serverContainer->getServerParameter(RequestInterface::REQUEST_URI_KEY);
        }
        return rtrim($baseURL . urldecode($uri), '/');
    }

    public function getQueryString(): string
    {
        if ($this->serverContainer->hasServerParameter(RequestInterface::QUERY_STRING_KEY)) {
            return urldecode(
                $this->serverContainer->getServerParameter(RequestInterface::QUERY_STRING_KEY)
            );
        }
        return '';
    }

    public function getBaseURL(): string
    {
        $baseURL = '';
        if ($this->serverContainer->hasServerParameter(RequestInterface::REQUEST_SCHEME_KEY)) {
            $baseURL = $this->serverContainer->getServerParameter(
                    RequestInterface::REQUEST_SCHEME_KEY
                ) . '://';
        }
        if ($this->serverContainer->hasServerParameter(RequestInterface::HTTP_HOST_KEY)) {
            $baseURL .= $this->serverContainer->getServerParameter(RequestInterface::HTTP_HOST_KEY);
        }
        $baseURL = rtrim($baseURL, '/');
        return $baseURL . '/';
    }

    public function getURIWithQueries(): string
    {
        if ($this->getQueryString() === '') {
            return $this->getPath();
        }
        return $this->getPath() . '?' . $this->getQueryString();
    }
}