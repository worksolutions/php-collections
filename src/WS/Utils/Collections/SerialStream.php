<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use RuntimeException;
use WS\Utils\Collections\Functions\Reorganizers;

class SerialStream implements Stream
{
    /**
     * @var ListSequence
     */
    private $list;

    public function __construct(Collection $collection)
    {
        $this->list = new ArrayList();
        $this->list->addAll($collection);
    }

    /**
     * @inheritDoc
     */
    public function each(callable $consumer): Stream
    {
        $i = 0;
        foreach ($this->list as $item) {
            $consumer($item, $i++);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function filter(callable $predicate): Stream
    {
        $collection = $this->list;
        $this->list = $this->emptyList();

        foreach ($collection as $item) {
            if ($predicate($item)) {
                $this->list->add($item);
            }
        }

        return $this;
    }

    public function reorganize(callable $reorganizer): Stream
    {
        $reorganizedCollection = $reorganizer($this->list);
        if (! $reorganizedCollection instanceof Collection) {
            throw new RuntimeException('Result set of reorganizer call must be instance of Collection interface');
        }
        $this->list = $reorganizedCollection;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function allMatch(callable $predicate): bool
    {
        foreach ($this->list as $item) {
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
        foreach ($this->list as $item) {
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
        $collection = $this->list;
        $this->list = $this->emptyList();

        foreach ($collection as $item) {
            $this->list->add($converter($item));
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sort(callable $comparator): Stream
    {
        $collection = $this->getCollection();
        $this->list = $this->emptyList();

        $array = $collection->toArray();
        usort($array, $comparator);
        foreach ($array as $item) {
            $this->list->add($item);
        }

        return $this;
    }

    public function sortBy(callable $extractor): Stream
    {
        $values = [];
        $map = [];
        $this->each(static function ($el) use ($extractor, & $map, & $values) {
            $value = $extractor($el);
            if (!is_scalar($value)) {
                throw new RuntimeException('Only scalar value can be as result of sort extractor');
            }
            $values[] = $value;
            $map[$value.''][] = $el;
        });
        sort($values);
        $newList = $this->emptyList();
        foreach ($values as $value) {
            $els = $map[$value] ?? [];
            $newList->addAll($els);
        }
        $this->list = $newList;

        return $this;
    }

    public function sortByDesc(callable $extractor): Stream
    {
        $this->sortBy($extractor)
            ->reverse();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function sortDesc(callable $comparator): Stream
    {
        $this->sort($comparator)
            ->reverse();

        return $this;
    }

    public function reverse(): Stream
    {
        $size = $this->list->size();
        /** @var ListSequence $list */
        $list = $this->list->copy();
        $this->walk(static function ($head, $index) use ($list, $size) {
            $tailIndex = $size - $index - 1;
            $tail = $list->get($tailIndex);
            $list->set($tail, $index);
            $list->set($head, $tailIndex);
        }, (int)($size/2));
        $this->list = $list;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function collect(callable $collector)
    {
        return $collector($this->getCollection());
    }

    /**
     * @inheritDoc
     */
    public function findAny()
    {
        return $this->reorganize(Reorganizers::random(1))
            ->findFirst();
    }

    /**
     * @inheritDoc
     */
    public function findFirst()
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($this->list as $item) {
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
        foreach ($this->list as $item) {
            $accumulate = $accumulator($item, $accumulate);
        }
        return $accumulate;
    }

    public function getCollection(): Collection
    {
        return $this->list;
    }

    private function emptyList(): Collection
    {
        return ArrayList::of();
    }

    public function findLast()
    {
        $array = $this->list->toArray();
        return array_pop($array);
    }

    public function walk(callable $consumer, ?int $limit = null): Stream
    {
        $iterationsCount = $limit ?? $this->list->size();
        foreach ($this->list as $i => $item) {
            $consumerRes = $consumer($item, $i);
            if ($consumerRes === false) {
                break;
            }
            if ($i + 1 >= $iterationsCount) {
                break;
            }
        }

        return $this;
    }

    public function limit(int $size): Stream
    {
        $newCollection = $this->emptyList();
        $this->walk(static function ($el) use ($newCollection) {
            $newCollection->add($el);
        }, $size);

        $this->list = $newCollection;
        return $this;
    }

    public function when(bool $condition): Stream
    {
        if (!$condition) {
            return new DummyStreamDecorator($this);
        }

        return $this;
    }

    public function always(): Stream
    {
        return $this;
    }
}
