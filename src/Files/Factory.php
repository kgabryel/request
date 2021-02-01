<?php

namespace Frankie\Request\Files;

use Ds\Map;
use Frankie\SizeParser\SizeParser;

abstract class Factory
{
    protected Map $files;
    protected array $params;

    abstract public function __construct(array $file);

    public static function getFactory(array $param): self
    {
        if (\is_array($param[array_key_first($param)])) {
            return new ArrayFactory($param);
        }
        return new SingleFactory($param);
    }

    public function get(): Map
    {
        return $this->files;
    }

    abstract public function build(SizeParser $sizeParser, string $index): self;
}