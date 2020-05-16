<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;


use RuntimeException;

class ArrayStack implements Stack
{

    /**
     * @var array
     */
    private $items;

    public function __construct(array $items)
    {
        $this->items = array_values($items);
    }

    /**
     * Adds element to the top of stack
     *
     * @param $element
     *
     * @return bool
     */
    public function push($element): bool
    {
        return $this->add($element);
    }

    /**
     * Gets element from the top of stack
     *
     * @return mixed
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Stack is empty');
        }

        return array_pop($this->items);
    }

    /**
     * Retrieves, but does not remove
     *
     * @return mixed
     */
    public function peek()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Stack is empty');
        }

        return $this->items[count($this->items) - 1];
    }

    public function add($element): bool
    {
        $this->items[] = $element;

        return true;
    }

    public function merge(Collection $collection): bool
    {
        $this->items = array_merge($this->items, array_values($collection->toArray()));

        return true;
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function remove($element): bool
    {
        if ($this->isEmpty()) {
            return false;
        }
        $index = $this->size() - 1;
        while ($index >= 0) {
            if ($this->items[$index] === $element) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                return true;
            }
            $index--;
        }

        return false;
    }

    public function contains($element): bool
    {
        return in_array($element, $this->items, true);
    }

    public function equals(Collection $collection): bool
    {
        return $this->toArray() === $collection->toArray();
    }

    public function isEmpty(): bool
    {
        return $this->size() === 0;
    }

    public function stream(): Stream
    {

    }

    public function toArray(): array
    {
        return array_reverse($this->items);
    }

    public function getIterator()
    {
        $items = $this->toArray();
        $index = 0;
        while ($index < $this->size()) {
            yield $items[$index++];
        }
    }

    public function size() {
        return count($this->items);
    }
}
