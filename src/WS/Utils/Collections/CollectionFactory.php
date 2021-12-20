<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use Iterator;
use IteratorAggregate;
use RuntimeException;
use WS\Utils\Collections\Exception\UnsupportedException;

class CollectionFactory
{
    use CollectionConstructorTrait;

    /**
     * Returns collection generated with generator <f(int $index): mixed> for $times
     * @param int $times
     * @param callable|null $generator
     * @return Collection
     */
    public static function generate(int $times, ?callable $generator = null): Collection
    {
        if ($times < 0) {
            throw new RuntimeException('The count of values ($times) must be a positive value');
        }
        $generator = $generator ?? static function (int $index) {
            return $index;
        };

        $collection = self::toCollection();
        for ($i = 0; $i < $times; $i++) {
            $collection->add($generator($i));
        }

        return $collection;
    }

    /**
     * Generate collection of int numbers between $from and $to. If $to arg is absent $from - is count of numbers
     * @param int $from
     * @param int|null $to
     * @return Collection
     */
    public static function numbers(int $from, ?int $to = null): Collection
    {
        if ($to === null) {
            $to = $from - 1;
            $from = 0;
        }

        if ($from > $to) {
            throw new RuntimeException('FROM need to be less than TO');
        }
        $list = new ArrayList();
        for ($i = $from; $i <= $to; $i++) {
            $list->add($i);
        }

        return $list;
    }

    public static function from(array $values): Collection
    {
        return new ArrayList($values);
    }

    public static function fromStrict(array $values): Collection
    {
        return new ArrayStrictList($values);
    }

    /**
     * @throws UnsupportedException
     */
    public static function fromIterable(iterable $iterable): Collection
    {
        if (self::isStatePatternIterator($iterable)) {
            if ($iterable instanceof IteratorAggregate) {
                /** @noinspection PhpUnhandledExceptionInspection */
                $iterable = $iterable->getIterator();
            }
            if (!$iterable instanceof Iterator) {
                throw new UnsupportedException('Only Iterator interface can be applied to IteratorCollection');
            }
            return new IteratorCollection($iterable);
        }
        $list = ArrayList::of();
        foreach ($iterable as $item) {
            $list->add($item);
        }

        return $list;
    }

    public static function empty(): Collection
    {
        return ArrayList::of();
    }

    private static function isStatePatternIterator(iterable $iterable): bool
    {
        $i = 2;
        $lastItem = null;
        foreach ($iterable as $item) {
            if ($i === 0) {
                break;
            }
            if (is_object($item) && $item === $lastItem) {
                return true;
            }
            $lastItem = $item;
            $i--;
        }
        return false;
    }
}
