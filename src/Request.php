<?php

namespace Frankie\Request;

use Frankie\Request\Data\DataContainer;
use Frankie\Request\Files\FilesContainer;
use Frankie\Request\Headers\HeadersContainer;
use Frankie\Request\HttpMethod\HttpMethodInfo;
use Frankie\Request\Path\PathInfo;
use Frankie\Request\Queries\QueriesContainer;
use Frankie\Request\Server\ServerContainer;
use Frankie\SizeParser\SizeParser;

class Request implements RequestInterface
{
    private ServerContainer $server;
    private QueriesContainer $queries;
    private PathInfo $pathInfo;
    private DataContainer $data;
    private FilesContainer $files;
    private HeadersContainer $headers;
    private HttpMethodInfo $httpMethodInfo;

    private function __construct(
        bool $override, SizeParser $sizeParser, array $get = [], array $post = [],
        array $files = [], array $server = []
    )
    {
        $this->queries = new QueriesContainer($get);
        $this->server = new ServerContainer($server);
        $this->files = new FilesContainer($files, $sizeParser);
        $this->pathInfo = new PathInfo($this->server);
        $this->data = new DataContainer($get, $post);
        $this->headers = new HeadersContainer($server);
        $this->httpMethodInfo = new HttpMethodInfo(
            $this->server->getServerParameter(HttpMethodInfo::REQUEST_METHOD_KEY),
            HttpMethodInfo::getUsedHttpMethod(
                $override,
                $this->server,
                $this->headers
            )
        );
    }

    public static function createFromGlobal(SizeParser $sizeParser, bool $override = true): self
    {
        return new Request($override, $sizeParser, $_GET, $_POST, $_FILES, $_SERVER);
    }

    public function getHttpInfo(): HttpMethodInfo
    {
        return $this->httpMethodInfo;
    }

    public function getQueries(): QueriesContainer
    {
        return $this->queries;
    }

    public function getUploadedFiles(): FilesContainer
    {
        return $this->files;
    }

    public function getServerParameters(): ServerContainer
    {
        return $this->server;
    }

    public function getHeaders(): HeadersContainer
    {
        return $this->headers;
    }

    public function getPathInfo(): PathInfo
    {
        return $this->pathInfo;
    }

    public function getData(): DataContainer
    {
        return $this->data;
    }

    public function setContent(array $content): RequestInterface
    {
        $this->data->merge($content);
        return $this;
    }
}
