<?php

namespace WS\Utils\Collections;

use WS\Utils\Collections\Exception\UnsupportedException;

class IteratorStream implements Stream
{
    /**
     * @var IteratorCollection
     */
    private $collection;

    /**
     * @var int[]
     */
    private $excluded = [];

    public function __construct(IteratorCollection $collection)
    {
        $this->collection = $collection;
    }

    public function each(callable $consumer): Stream
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $item = $iterator->current();
                $consumer($item);
            }
            $iterator->next();
            $i++;
        }

        return $this;
    }

    public function walk(callable $consumer, ?int $limit = null): Stream
    {
        $iterationsCount = $limit ?? $this->collection->size();
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();

        $i = 0;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $item = $iterator->current();
                $consumerRes = $consumer($item, $i);
                if ($consumerRes === false) {
                    break;
                }
                if ($i + 1 >= $iterationsCount) {
                    break;
                }
            }

            $iterator->next();
            $i++;
        }
        return $this;
    }

    public function filter(callable $predicate): Stream
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $item = $iterator->current();
                !$predicate($item) && $this->exclude($i);
            }
            $iterator->next();
            $i++;
        }

        return $this;
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function reorganize(callable $reorganizer): Stream
    {
        throw new UnsupportedException();
    }

    public function allMatch(callable $predicate): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $current = $iterator->current();
                if (!$predicate($current)) {
                    return false;
                }
            }
            $iterator->next();
            $i++;
        }
        return true;
    }

    public function anyMatch(callable $predicate): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $current = $iterator->current();
                if ($predicate($current)) {
                    return true;
                }
            }
            $iterator->next();
            $i++;
        }
        return false;
    }

    /**
     * @throws UnsupportedException
     */
    public function map(callable $converter): Stream
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        $list = new ArrayList();
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $item = $iterator->current();
                $converterRes = $converter($item);
                if ($converterRes === $item) {
                    throw new UnsupportedException('Item must be another different from sourced');
                }
                $list->add($converterRes);
            }
            $iterator->next();
            $i++;
        }

        return new SerialStream($list);
    }

    /**
     * @codeCoverageIgnore
     */
    public function collect(callable $collector)
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function findAny()
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function findFirst(callable $filter = null)
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function findLast()
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function min(callable $comparator)
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function max(callable $comparator)
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function sort(callable $comparator): Stream
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function sortBy(callable $extractor): Stream
    {
        throw new UnsupportedException();
    }

    /**
     * @codeCoverageIgnore
     */
    public function sortDesc(callable $comparator): Stream
    {
        throw new UnsupportedException();
    }

    /**
     * @codeCoverageIgnore
     */
    public function sortByDesc(callable $extractor): Stream
    {
        throw new UnsupportedException();
    }

    /**
     * @codeCoverageIgnore
     */
    public function reverse(): Stream
    {
        throw new UnsupportedException();
    }

    public function reduce(callable $accumulator, $initialValue = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        $accumulate = $initialValue;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                $accumulate = $accumulator($iterator->current(), $accumulate);
            }

            $i++;
            $iterator->next();
        }

        return $accumulate;
    }

    public function limit(int $size): Stream
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $iterator = $this->collection->getIterator();
        $iterator->rewind();
        $i = 0;
        $countdown = $size;
        while ($iterator->valid()) {
            if (!$this->isExcluded($i)) {
                if ($countdown <= 0) {
                    $this->exclude($i);
                }
                $countdown--;
            }
            $iterator->next();
            $i++;
        }

        return $this;
    }

    public function when(bool $condition): Stream
    {
        if (!$condition) {
            return new DummyStreamDecorator($this);
        }

        return $this;
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function getCollection(): Collection
    {
        throw new UnsupportedException();
    }

    public function always(): Stream
    {
        return $this;
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function toArray(): array
    {
        throw new UnsupportedException();
    }

    /**
     * @throws UnsupportedException
     * @codeCoverageIgnore
     */
    public function getSet(): Set
    {
        throw new UnsupportedException();
    }

    /**
     * @param int $index
     * @return void
     */
    private function exclude(int $index): void
    {
        $this->excluded[$index] = $index;
    }

    private function isExcluded(int $index): bool
    {
        return isset($this->excluded[$index]);
    }
}
