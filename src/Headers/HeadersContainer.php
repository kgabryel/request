<?php

namespace Frankie\Request\Headers;

use Ds\Map;
use Ds\Vector;
use Frankie\Request\HeaderWithQ;
use Frankie\Request\RequestInterface;
use OutOfBoundsException;

class HeadersContainer
{
    private Map $headers;
    private Vector $acceptedMimeType;
    private Vector $acceptedCharset;
    private Vector $acceptedEncoding;
    private Vector $acceptedLanguage;
    public function __construct(array $server)
    {
        $this->headers = new Map();
        foreach ($server as $key => $val) {
            $key = (string)$key;
            if (strncmp($key, 'HTTP_', 5) !== 0) {
                continue;
            }
            $key = str_replace('_', '-', strtolower(substr($key, 5)));
            $this->headers->put($key, $val);
        }

        $this->acceptedMimeType = new Vector();
        $this->acceptedCharset = new Vector();
        $this->acceptedEncoding = new Vector();
        $this->acceptedLanguage = new Vector();
        $acceptedMimeType = $this->headers[RequestInterface::ACCEPT] ?? '';
        $this->setQHeader($acceptedMimeType, $this->acceptedMimeType);
        $acceptedCharset = $this->headers[RequestInterface::ACCEPTED_CHARSET] ?? '';
        $this->setQHeader($acceptedCharset, $this->acceptedCharset);
        $acceptedEncoding = $this->headers[RequestInterface::ACCEPTED_ENCODING] ?? '';
        $this->setQHeader($acceptedEncoding, $this->acceptedEncoding);
        $acceptedLanguage = $this->headers[RequestInterface::ACCEPTED_LANGUAGE] ?? '';
        $this->setQHeader($acceptedLanguage, $this->acceptedLanguage);
    }

    private function setQHeader(string $headerString, Vector $to): void
    {
        $tmp = new Vector();
        $parts = explode(',', $headerString);
        foreach ($parts as $parts2) {
            if ($parts2 !== '') {
                $part = explode(';', $parts2);
                $qHeader = $this->createQHeader($part);
                if ($qHeader !== null) {
                    $tmp->push($qHeader);
                }
            }
        }
        $this->pushQHeader($to, $tmp);
    }

    private function createQHeader(array $params): ?HeaderWithQ
    {
        $value = trim($params[0], '');
        if (\count($params) === 1) {
            return new HeaderWithQ($value);
        }
        $q = trim($params[1], '');
        if (strncmp($q, 'q=', 2) === 0) {
            return new HeaderWithQ($value, str_replace('q=', '', $q));
        }
        return null;
    }

    private function pushQHeader(Vector $to, Vector $from): void
    {
        $vals = [
            1.0,
            0.9,
            0.8,
            0.7,
            0.6,
            0.5,
            0.4,
            0.3,
            0.2,
            0.1,
            0.0
        ];
        foreach ($vals as $q) {
            foreach ($from as $val) {
                if ($val->getQ() === $q) {
                    $to->push($val);
                }
            }
        }
    }

    public function getHeaders(): Map
    {
        return $this->headers;
    }

    public function hasHeader(string $key): bool
    {
        return $this->headers->hasKey(strtolower($key));
    }

    public function getHeader(string $key): string
    {
        if (!$this->headers->hasKey(strtolower($key))) {
            throw new OutOfBoundsException("Undefined key: $key.");
        }
        return $this->headers->get(strtolower($key));
    }

    public function getPreferredMimeType(): ?HeaderWithQ
    {
        if ($this->acceptedMimeType->isEmpty()) {
            return null;
        }
        return $this->acceptedMimeType->first();
    }

    public function getMimeTypes(): Vector
    {
        return $this->acceptedMimeType;
    }

    public function getPreferredCharset(): ?HeaderWithQ
    {
        if ($this->acceptedCharset->isEmpty()) {
            return null;
        }
        return $this->acceptedCharset->first();
    }

    public function getCharsets(): Vector
    {
        return $this->acceptedCharset;
    }

    public function getPreferredEncoding(): ?HeaderWithQ
    {
        if ($this->acceptedEncoding->isEmpty()) {
            return null;
        }
        return $this->acceptedEncoding->first();
    }

    public function getEncodings(): Vector
    {
        return $this->acceptedEncoding;
    }


    public function getPreferredLanguage(): ?HeaderWithQ
    {
        if ($this->acceptedLanguage->isEmpty()) {
            return null;
        }
        return $this->acceptedLanguage->first();
    }


    public function getLanguages(): Vector
    {
        return $this->acceptedLanguage;
    }

}