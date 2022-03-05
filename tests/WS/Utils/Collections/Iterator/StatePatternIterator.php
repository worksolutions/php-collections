<?php

namespace WS\Utils\Collections\Iterator;

use Iterator;
use IteratorAggregate;

class StatePatternIterator implements IteratorAggregate
{
    /**
     * @var int
     */
    private $count;

    public function __construct(int $count)
    {
        $this->count = $count;
    }

    public function getIterator()
    {
        return new class($this->count) implements Iterator, ValueKeeper {
            private $current;
            private $count;

            public function __construct(int $count)
            {
                $this->count = $count;
                $this->rewind();
            }

            public function current()
            {
                return $this;
            }

            public function next()
            {
                $this->current++;
            }

            public function key()
            {
                return $this->current;
            }

            public function valid(): bool
            {
                return $this->current < $this->count;
            }

            public function rewind()
            {
                $this->current = 0;
            }

            public function getValue()
            {
                return $this->current;
            }
        };
    }
}
