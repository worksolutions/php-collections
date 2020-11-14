<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

abstract class AbstractCollection implements Collection
{
    protected $elements = [];
    protected $pointer = -1;

    public function __construct(?array $elements = null)
    {
        if ($elements === null) {
            return;
        }
        $this->setElements($elements);
    }

    public static function of(...$elements): self
    {
        return new static($elements ?: null);
    }

    public function add($element): bool
    {
        if ($this->pointer === PHP_INT_MAX) {
            return false;
        }
        $this->pointer++;
        $this->elements[] = $element;
        return true;
    }

    public function addAll(iterable $elements): bool
    {
        foreach ($elements as $element) {
            $this->elements[] = $element;
        }
        $newPointer = count($this->elements) - 1;
        if ($newPointer > PHP_INT_MAX) {
            $this->elements = array_slice($this->elements, 0, $this->pointer);
        } else {
            $this->pointer = $newPointer;
        }
        return true;
    }

    public function merge(Collection $collection): bool
    {
        return $this->addAll($collection->toArray());
    }

    public function clear(): void
    {
        $this->setElements([]);
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
        return $this->pointer + 1;
    }

    public function isEmpty(): bool
    {
        return $this->pointer === -1;
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

    protected function setElements(array $elements): void
    {
        $this->elements = array_values($elements);
        $this->pointer = count($elements) - 1;
    }

    protected function getElements(): array
    {
        return $this->elements;
    }

    abstract public function stream(): Stream;

    abstract public function parallelStream($workersPool = null): Stream;

}
