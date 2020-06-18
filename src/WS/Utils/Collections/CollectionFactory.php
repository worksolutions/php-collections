<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use RuntimeException;

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
     * Returns stream with generator
     * @param callable|null $generator
     * @return Stream
     */
    public static function stream(?callable $generator = null): Stream
    {
        return new SerialStream(self::toCollection());
    }

    /**
     * Generate collection of int numbers between $from and $to. If $to arg is absent $from - is count of numbers
     * @param int $from
     * @param int $to
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
}
