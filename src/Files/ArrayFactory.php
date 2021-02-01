<?php

namespace Frankie\Request\Files;

use Ds\Map;
use Ds\Stack;
use Frankie\SizeParser\SizeParser;

class ArrayFactory extends Factory
{
    private Stack $keys;

    public function __construct(array $file)
    {
        $this->params = $file;
        $this->files = new Map();
        $this->keys = new Stack(array_keys($this->params[array_key_first($this->params)]));
    }

    public function build(SizeParser $sizeParser, string $index): Factory
    {
        $files = new Map();
        while (!$this->keys->isEmpty()) {
            $innerIndex = (int)$this->keys->pop();
            $file = [];
            foreach ($this->params as $key => $val) {
                if (isset($this->params[$key][$innerIndex])) {
                    $file[$key] = $this->params[$key][$innerIndex];
                }
            }
            $factory = new SingleFactory($file);
            $files->put(
                $innerIndex,
                $factory->build($sizeParser, $innerIndex)
                    ->get()
            );
        }
        $this->files->put($index,$files);
        return $this;
    }
}
