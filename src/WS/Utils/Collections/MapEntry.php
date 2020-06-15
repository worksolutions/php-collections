<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

class MapEntry
{
    private $key;
    private $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @param mixed $key
     * @return MapEntry
     */
    public function setKey($key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $value
     * @return MapEntry
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}