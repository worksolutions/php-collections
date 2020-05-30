<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 * @license MIT
 */

namespace WS\Utils\Collections;

class ArrayMap implements Map
{
    private array $entrySet;

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
        return ArrayList::of($this->entrySet);
    }

    public function keySet(): Set
    {
        // TODO: Implement keySet() method.
    }
}
