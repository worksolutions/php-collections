<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

abstract class AbstractList implements Collection
{

    public static function of(...$elements): self
    {

    }

    public function add($element): bool
    {
        // TODO: Implement add() method.
    }

    public function merge(Collection $collection): bool
    {
        // TODO: Implement merge() method.
    }

    public function clear(): void
    {
        // TODO: Implement clear() method.
    }

    public function remove($element): bool
    {
        // TODO: Implement remove() method.
    }

    public function contains($element): bool
    {
        // TODO: Implement contains() method.
    }

    public function equals(Collection $collection): bool
    {
        // TODO: Implement equals() method.
    }

    public function isEmpty(): bool
    {
        // TODO: Implement isEmpty() method.
    }

    public function toArray(): array
    {
        // TODO: Implement toArray() method.
    }

    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    abstract public function stream(): Stream;
}
