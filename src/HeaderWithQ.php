<?php

namespace Frankie\Request;

use InvalidArgumentException;

final class HeaderWithQ
{
    /** @var string $value */
    private $value;

    /** @var float $q */
    private $q;

    /**
     * HeaderWithQ constructor.
     *
     * @param string $value
     * @param float $q
     */
    public function __construct(string $value, float $q = 1.0)
    {
        if ($q < 0 || $q > 1) {
            throw new InvalidArgumentException('Q parameter have to be between 0 and 1.');
        }
        if (($q * 10) % floor($q * 10) !== 0) {
            throw new InvalidArgumentException('Q step have to be 0.1.');
        }
        $this->value = $value;
        $this->q = $q;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return float
     */
    public function getQ(): float
    {
        return $this->q;
    }


}