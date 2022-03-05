<?php

namespace WS\Utils\Collections;

use Iterator;
use WS\Utils\Collections\Exception\UnsupportedException;

/**
 * Collection which support Traversable interface
 */
class IteratorCollection implements Collection
{

    /**
     * @var Iterator
     */
    private $iterator;

    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function add($element): bool
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function addAll(iterable $elements): bool
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function merge(Collection $collection): bool
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function clear(): void
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function remove($element): bool
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function contains($element): bool
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function equals(Collection $collection): bool
    {
        throw new UnsupportedException();
    }

    public function size(): int
    {
        $this->iterator->rewind();
        $count = 0;
        while ($this->iterator->valid()) {
            $this->iterator->next();
            $count++;
        }

        return $count;
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    public function stream(): Stream
    {
        return new IteratorStream($this);
    }

    /**
     * @codeCoverageIgnore
     * @return array
     */
    public function toArray(): array
    {
        throw new UnsupportedException();
    }

    /**
     * @codeCoverageIgnore
     * @return Collection
     */
    public function copy(): Collection
    {
        throw new UnsupportedException();
    }

    public function getIterator()
    {
        return $this->iterator;
    }
}
