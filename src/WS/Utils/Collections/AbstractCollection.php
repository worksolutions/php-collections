<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

abstract class AbstractCollection implements Collection
{
    protected $elements = [];

    public function __construct(?array $elements = null)
    {
        if ($elements === null) {
            return;
        }
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    public static function of(...$elements): self
    {
        return new static($elements ?: null);
    }

    public function add($element): bool
    {
        $beforeSize = count($this->elements);
        $this->elements[] = $element;
        return $beforeSize < count($this->elements);
    }

    public function addAll(iterable $elements): bool
    {
        $res = true;
        foreach ($elements as $element) {
            !$this->add($element) && $res = false;
        }
        return $res;
    }

    public function merge(Collection $collection): bool
    {
        $this->elements = array_merge($this->toArray(), $collection->toArray());
        return true;
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function equals(Collection $collection): bool
    {
        return $this->toArray() === $collection->toArray();
    }

    public function size(): int
    {
        return count($this->elements);
    }

    public function isEmpty(): bool
    {
        return !$this->size();
    }

    public function toArray(): array
    {
        return array_values($this->elements);
    }

    public function getIterator()
    {
        yield from $this->toArray();
    }

    public function copy(): Collection
    {
        return clone $this;
    }

    abstract public function stream(): Stream;
}
