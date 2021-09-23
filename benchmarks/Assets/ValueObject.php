<?php

namespace Benchmarks\Assets;

class ValueObject
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
