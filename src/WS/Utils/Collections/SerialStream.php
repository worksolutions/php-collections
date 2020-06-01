<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use WS\Utils\Collections\Functions\CollectionAwareFunction;
use WS\Utils\Collections\Functions\Predicates;

class SerialStream implements Stream
{
    /**
     * @var Collection
     */
    private $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection->copy();
    }

    /**
     * @inheritDoc
     */
    public function each(callable $consumer): Stream
    {
        foreach ($this->collection as $item) {
            $consumer($item);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function filter(callable $predicate): Stream
    {
        $collection = $this->collection;
        $this->collection = $this->emptyCollection();

        if ($predicate instanceof CollectionAwareFunction) {
            $predicate->withCollection($collection);
        }

        foreach ($collection as $item) {
            if ($predicate($item)) {
                $this->collection->add($item);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function allMatch(callable $predicate): bool
    {
        foreach ($this->collection as $item) {
            if (!$predicate($item)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function anyMatch(callable $predicate): bool
    {
        foreach ($this->collection as $item) {
            if ($predicate($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function map(callable $converter): Stream
    {
        $collection = $this->collection;
        $this->collection = $this->emptyCollection();

        foreach ($collection as $item) {
            $this->collection->add($converter($item));
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sort(callable $comparator): Stream
    {
        $collection = $this->getCollection();
        $this->collection = $this->emptyCollection();

        $array = $collection->toArray();
        usort($array, $comparator);
        foreach ($array as $item) {
            $this->collection->add($item);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sortDesc(callable $comparator): Stream
    {
        $collection = $this->getCollection();
        $this->collection = $this->emptyCollection();

        $array = $collection->toArray();
        usort($array, static function ($a, $b) use ($comparator): int {
            return -1 * $comparator($a, $b);
        });
        foreach ($array as $item) {
            $this->collection->add($item);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function aggregate(callable $aggregator)
    {
        return $aggregator($this->getCollection());
    }

    /**
     * @inheritDoc
     */
    public function findAny()
    {
        return $this->filter(Predicates::random(1))
            ->findFirst();
    }

    /**
     * @inheritDoc
     */
    public function findFirst()
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($this->collection as $item) {
            return $item;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function min(callable $comparator)
    {
        $collection = $this->getCollection();
        if ($collection->size() === 0) {
            return null;
        }

        $el = $this->findFirst();

        if ($collection->size() === 1) {
            return $el;
        }

        foreach ($collection as $item) {
            if ($comparator($item, $el) < 0) {
                $el = $item;
            }
        }

        return $el;
    }

    /**
     * @inheritDoc
     */
    public function max(callable $comparator)
    {
        $collection = $this->getCollection();
        if ($collection->size() === 0) {
            return null;
        }

        $el = $this->findFirst();

        if ($collection->size() === 1) {
            return $el;
        }

        foreach ($collection as $item) {
            if ($comparator($item, $el) > 0) {
                $el = $item;
            }
        }

        return $el;
    }

    /**
     * @inheritDoc
     */
    public function reduce(callable $accumulator)
    {
        $accumulate = null;
        foreach ($this->collection as $item) {
            $accumulate = $accumulator($item, $accumulate);
        }
        return $accumulate;
    }

    /**
     * @inheritDoc
     */
    public function parallel(): Stream
    {
        return $this;
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    private function emptyCollection(): Collection
    {
        return ArrayList::of();
    }
}