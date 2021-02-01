<?php

namespace Frankie\Request\Files;

use Ds\Map;
use Frankie\SizeParser\SizeParser;
use Frankie\UploadedFile\UploadedFile;
use OutOfBoundsException;

class FilesContainer
{
    private Map $files;

    public function __construct(array $files, SizeParser $sizeParser)
    {
        $this->files = new Map();
        foreach ($files as $key => $value) {
            $factory = Factory::getFactory($value);
            $factory->build($sizeParser, $key);
            $this->files->putAll($factory->get());
        }
    }

    public function getFiles(): Map
    {
        return $this->files;
    }

    /**
     * @param string $key
     *
     * @return UploadedFile|Map
     */
    public function getFile(string $key)
    {
        if (!$this->hasFile($key)) {
            throw new OutOfBoundsException("Undefined key: $key.");
        }
        return $this->files[$key];
    }

    public function hasFile(string $key): bool
    {
        return $this->files->hasKey($key);
    }
}