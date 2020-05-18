<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

abstract class AbstractList implements Collection
{
    protected array $elements = [];

    public static function of(...$elements): self
    {
        $list = new static();
        foreach ($elements as $element) {
            $list->add($element);
        }
        return $list;
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

    abstract public function stream(): Stream;
}
