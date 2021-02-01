<?php

namespace Frankie\Request\Files;

use Ds\Map;
use Frankie\SizeParser\SizeParser;
use Frankie\UploadedFile\UploadedFile;

class SingleFactory extends Factory
{
    public function __construct(array $file)
    {
        $this->params = $file;
        $this->files = new Map();
    }

    public function build(SizeParser $sizeParser, string $index): self
    {
        $this->files->put($index, new UploadedFile($this->params, $sizeParser));
        return $this;
    }
}
