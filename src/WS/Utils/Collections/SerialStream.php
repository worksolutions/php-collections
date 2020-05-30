<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

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
    public function aggregate(callable $aggregator)
    {
        $accumulate = null;
        foreach ($this->collection as $item) {
            $accumulate = $aggregator($item, $accumulate);
        }
        return $accumulate;
    }

    /**
     * @inheritDoc
     */
    public function findAny()
    {
        // TODO: Implement findAny() method.
    }

    /**
     * @inheritDoc
     */
    public function findFirst()
    {
        // TODO: Implement findFirst() method.
    }

    /**
     * @inheritDoc
     */
    public function min(callable $comparator)
    {
        // TODO: Implement min() method.
    }

    /**
     * @inheritDoc
     */
    public function max(callable $comparator)
    {
        // TODO: Implement max() method.
    }

    /**
     * @inheritDoc
     */
    public function reduce(callable $accumulator)
    {
        // TODO: Implement reduce() method.
    }

    /**
     * @inheritDoc
     */
    public function parallel(): Stream
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function of(...$elements): Stream
    {
        return new self(ArrayList::of());
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