<?php

namespace Frankie\Request;

interface ParserProviderInterface
{
    /**
     * @return array
     */
    public static function get():array;
}