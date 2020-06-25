<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use RuntimeException;

class ImmutableList implements ListSequence
{

    private $decoratedList;

    public function __construct(?array $elements = null)
    {
        $this->decoratedList = new ArrayList($elements);
    }

    public static function fromCollection(Collection $collection): self
    {
        return new static($collection->toArray());
    }

    public static function of(...$elements): self
    {
        return new static($elements ?: null);
    }
    
    public function stream(): Stream
    {
        return $this->decoratedList->stream();
    }

    public function remove($element): bool
    {
        throw $this->createBlockingException();
    }

    public function get(int $index)
    {
        return $this->decoratedList->get($index);
    }

    public function set($element, int $index)
    {
        throw $this->createBlockingException();
    }

    public function indexOf($element): ?int
    {
        return $this->decoratedList->indexOf($element);
    }

    public function lastIndexOf($element): ?int
    {
        return $this->decoratedList->lastIndexOf($element);
    }

    public function removeAt(int $index)
    {
       throw $this->createBlockingException();
    }

    private function createBlockingException(): RuntimeException
    {
        return new RuntimeException('Is immutable list. Everything modifier call is prohibited');
    }

    public function add($element): bool
    {
        throw  $this->createBlockingException();
    }

    public function addAll(iterable $elements): bool
    {
        throw $this->createBlockingException();
    }

    public function merge(Collection $collection): bool
    {
        throw $this->createBlockingException();
    }

    public function clear(): void
    {
        throw $this->createBlockingException();
    }

    public function contains($element): bool
    {
        return $this->decoratedList->contains($element);
    }

    public function equals(Collection $collection): bool
    {
        return $this->decoratedList->equals($collection);
    }

    public function size(): int
    {
        return $this->decoratedList->size();
    }

    public function isEmpty(): bool
    {
        return $this->decoratedList->isEmpty();
    }

    public function toArray(): array
    {
        return $this->decoratedList->toArray();
    }

    public function copy(): Collection
    {
        return $this->decoratedList->copy();
    }

    public function getIterator()
    {
        return $this->decoratedList->getIterator();
    }
}
