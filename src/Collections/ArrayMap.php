<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 * @license MIT
 */

namespace WS\Utils\Collections;

class ArrayMap implements Map
{
    private Collection $values;
    private Set $keySet;

    public function put($key, $value): bool
    {
        // TODO: Implement put() method.
    }

    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    public function values(): Collection
    {
        return $this->values;
    }

    public function keySet(): Set
    {
        return $this->keySet;
    }
}
