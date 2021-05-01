<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

class DummyStreamDecorator implements Stream
{

    /**
     * @var Stream
     */
    private $decoratedStream;

    public function __construct(Stream $originalStream)
    {
        $this->decoratedStream = $originalStream;
    }

    public function each(callable $consumer): Stream
    {
        return $this;
    }

    public function walk(callable $consumer, ?int $limit = null): Stream
    {
        return $this;
    }

    public function filter(callable $predicate): Stream
    {
        return $this;
    }

    public function reorganize(callable $reorganizer): Stream
    {
        return $this;
    }

    public function allMatch(callable $predicate): bool
    {
        return $this->decoratedStream->allMatch($predicate);
    }

    public function anyMatch(callable $predicate): bool
    {
        return $this->decoratedStream->anyMatch($predicate);
    }

    public function map(callable $converter): Stream
    {
        return $this;
    }

    public function collect(callable $collector)
    {
        return $this->decoratedStream->collect($collector);
    }

    public function findAny()
    {
        return $this->decoratedStream->findAny();
    }

    public function findFirst(callable $filter = null)
    {
        return $this->decoratedStream->findFirst();
    }

    public function findLast()
    {
        return $this->decoratedStream->findLast();
    }

    public function min(callable $comparator)
    {
        return $this->decoratedStream->min($comparator);
    }

    public function max(callable $comparator)
    {
        return $this->decoratedStream->max($comparator);
    }

    public function sort(callable $comparator): Stream
    {
        return $this;
    }

    public function sortBy(callable $extractor): Stream
    {
        return $this;
    }

    public function sortDesc(callable $comparator): Stream
    {
        return $this;
    }

    public function sortByDesc(callable $extractor): Stream
    {
        return $this;
    }

    public function reverse(): Stream
    {
        return $this;
    }

    public function reduce(callable $accumulator, $initialValue = null)
    {
        return $this->decoratedStream->reduce($accumulator, $initialValue);
    }

    public function limit(int $size): Stream
    {
        return $this;
    }

    public function getCollection(): Collection
    {
        return $this->decoratedStream->getCollection();
    }

    public function when(bool $condition): Stream
    {
        if ($condition) {
            return $this->decoratedStream;
        }

        return $this;
    }

    public function always(): Stream
    {
        return $this->decoratedStream;
    }
}
