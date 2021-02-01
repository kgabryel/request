<?php

namespace Frankie\Request;

use Frankie\Request\Data\DataContainer;
use Frankie\Request\Files\FilesContainer;
use Frankie\Request\Headers\HeadersContainer;
use Frankie\Request\HttpMethod\HttpMethodInfo;
use Frankie\Request\Path\PathInfo;
use Frankie\Request\Queries\QueriesContainer;
use Frankie\Request\Server\ServerContainer;

interface RequestInterface
{
    public const REQUEST_URI_KEY = 'REQUEST_URI';
    public const QUERY_STRING_KEY = 'QUERY_STRING';
    public const HTTP_HOST_KEY = 'HTTP_HOST';
    public const REQUEST_SCHEME_KEY = 'REQUEST_SCHEME';
    public const ACCEPT = 'accept';
    public const ACCEPTED_CHARSET = 'accepted-charset';
    public const ACCEPTED_ENCODING = 'accepted-encoding';
    public const ACCEPTED_LANGUAGE = 'accepted-language';
    public const REQUEST_SCHEME = 'REQUEST_SCHEME';
    public const HTTP_USER_AGENT = 'HTTP_USER_AGENT';
    public const SCRIPT_FILENAME = 'SCRIPT_FILENAME';
    public const HTTP_X_REQUESTED_WITH = 'HTTP_X_REQUESTED_WITH';
    public const XML_HTTP_REQUEST = 'xmlhttprequest';
    public const SERVER_PORT = 'SERVER_PORT';
    public const PHP_AUTH_USER = 'PHP_AUTH_USER';
    public const PHP_AUTH_PW = 'PHP_AUTH_PW';

    public function getHttpInfo(): HttpMethodInfo;

    public function getQueries(): QueriesContainer;

    public function getUploadedFiles(): FilesContainer;

    public function getServerParameters(): ServerContainer;

    public function getHeaders(): HeadersContainer;

    public function getData(): DataContainer;

    public function getPathInfo(): PathInfo;

    public function setContent(array $content): self;
}
