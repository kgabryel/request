<?php

namespace Frankie\Request\Data;

use Ds\Map;
use OutOfBoundsException;

class DataContainer
{
    private Map $data;

    public function __construct(array $get, array $post)
    {
        $this->data = new Map($get);
        $this->data->merge($post);
    }

    public function getAllData(): Map
    {
        return $this->data;
    }

    public function hasData(string $key): bool
    {
        return $this->data->hasKey($key);
    }

    public function getData(string $key)
    {
        if (!$this->data->hasKey($key)) {
            throw new OutOfBoundsException("Undefined key: $key.");
        }
        return $this->data[$key];
    }

    public function merge(array $content): void
    {
        $this->data = $this->data->merge($content);
    }
}