<?php

namespace Frankie\Request\HttpMethod;

use Frankie\Request\Headers\HeadersContainer;
use Frankie\Request\Server\ServerContainer;

class HttpMethodInfo
{
    public const REQUEST_METHOD_KEY = 'REQUEST_METHOD';
    public const X_HTTP_METHOD_OVERRIDE = 'x-http-method-override';
    public const POST_METHOD = 'POST';
    private string $realMethod;
    private string $httpMethod;

    public function __construct(string $realMethod, string $method)
    {
        $this->realMethod = $realMethod;
        $this->httpMethod = $method;
    }

    public static function getUsedHttpMethod(
        bool $override,
        ServerContainer $serverContainer,
        HeadersContainer $headersContainer
    ): string
    {
        if (
            $override && $headersContainer->hasHeader(
                self::X_HTTP_METHOD_OVERRIDE
            ) && (strtoupper(
                    $serverContainer->getServerParameter(self::REQUEST_METHOD_KEY)
                ) === self::POST_METHOD)
        ) {
            return strtoupper($headersContainer->getHeader(self::X_HTTP_METHOD_OVERRIDE));
        }
        return strtoupper($serverContainer->getServerParameter(self::REQUEST_METHOD_KEY));
    }

    public function getRealMethod(): string
    {
        return $this->realMethod;
    }

    public function isHttpMethod(string $httpMethod): bool
    {
        return strtoupper($httpMethod) === $this->getHttpMethod();
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }
}