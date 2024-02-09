<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;

use RuntimeException;
use WS\Utils\Collections\Iterator\Iterator;
use WS\Utils\Collections\Iterator\IteratorFactory;

class ArrayStack extends AbstractCollection implements Stack, IndexIterable
{

    use RemoveTraverseTrait;

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

        return array_pop($this->elements);
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

        return $this->elements[count($this->elements) - 1];
    }

    public function stream(): Stream
    {
        return new SerialStream($this);
    }

    public function parallelStream($workersPool = null): Stream
    {
        return new ParallelStream($this, $workersPool);
    }

    public function toArray(): array
    {
        return array_reverse($this->elements);
    }

    public function getIndexIterator(): Iterator
    {
        return IteratorFactory::reverseSequence($this->size());
    }

    protected function afterElementAdd($element): void
    {
    }

    protected function afterElementsSet(): void
    {
    }
}
