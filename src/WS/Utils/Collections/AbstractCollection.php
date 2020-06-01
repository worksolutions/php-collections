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
        return (bool)array_push($this->elements, $element);
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

    public function remove($element): bool
    {
        $key = array_search($element, $this->elements, true);
        if (false === $key) {
            return false;
        }
        unset($this->elements[$key]);
        return true;
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
        return $this->elements;
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
