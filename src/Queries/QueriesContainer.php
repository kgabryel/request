<?php

namespace Frankie\Request\Queries;

use Ds\Map;
use OutOfBoundsException;

class QueriesContainer
{
    private Map $queries;

    public function __construct(array $queries)
    {
        $this->queries = new Map($queries);
    }

    public function getQueries(): Map
    {
        return $this->queries;
    }

    public function getQuery(string $key): string
    {
        if (!$this->queries->hasKey($key)) {
            throw new OutOfBoundsException("Undefined key: $key");
        }
        return $this->queries->get($key);
    }

    public function hasQuery(string $key): bool
    {
        return $this->queries->hasKey($key);
    }
}