<?php

namespace Frankie\Request;

interface ParserInterface
{

    /**
     * ParserInterface constructor.
     *
     * @param string|null $data
     */
    public function __construct(?string $data = null);

    /**
     * @return ParserInterface
     */
    public function parse(): self;

    /** @return array */
    public function get(): array;

    /**
     * @return bool
     */
    public function isCorrect(): bool;
}
