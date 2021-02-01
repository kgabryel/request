<?php

namespace Frankie\Request\Server;

use Ds\Map;
use Frankie\Request\RequestInterface;
use OutOfBoundsException;

class ServerContainer
{
    private Map $serverParameters;

    public function __construct(array $server)
    {
        $this->serverParameters = new Map($server);
    }

    public function getServerParameters(): Map
    {
        return $this->serverParameters;
    }

    public function getServerParameter(string $key): string
    {
        if (!$this->hasServerParameter(strtolower($key))) {
            throw new OutOfBoundsException("Undefined key: $key.");
        }
        return $this->serverParameters->get(strtolower($key));
    }

    public function hasServerParameter(string $key): bool
    {
        return $this->serverParameters->hasKey($key);
    }

    public function getSchema(): string
    {
        return $this->serverParameters->get(RequestInterface::REQUEST_SCHEME, '');
    }

    public function getScriptName(): string
    {
        return $this->serverParameters->get(RequestInterface::SCRIPT_FILENAME, '');
    }

    public function getUserAgent(): string
    {
        return $this->serverParameters->get(RequestInterface::HTTP_USER_AGENT, '');
    }

    public function getPort(): int
    {
        return (int)$this->serverParameters->get(RequestInterface::SERVER_PORT, 0);
    }

    public function isAjax(): bool
    {
        return (strtolower(
                $this->serverParameters->get(RequestInterface::HTTP_X_REQUESTED_WITH, '')
            ) === RequestInterface::XML_HTTP_REQUEST);
    }

    public function getAuthUser(): string
    {
        return $this->serverParameters->get(RequestInterface::PHP_AUTH_USER, '');
    }

    public function getAuthPassword(): string
    {
        return $this->serverParameters->get(RequestInterface::PHP_AUTH_PW, '');
    }
}