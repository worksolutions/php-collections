<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;
use WS\Utils\Collections\ArrayList;
use WS\Utils\Collections\Collection;

class Reorganizers
{
    private static function collectionConstructor(? array $elements = null): Collection {
        return new ArrayList($elements);
    }

    public static function shuffle(): Closure
    {
        return static function (Collection $collection): Collection {
            $array = $collection->toArray();
            shuffle($array);

            return self::collectionConstructor($array);
        };
    }

    /**
     * Returns Closure of random collection
     * @param int $count
     * @return Closure
     */
    public static function random(int $count = 1): Closure
    {
        return static function (Collection $collection) use ($count): Collection {
            /**
             * Collection
             */
            $resultCollection = self::collectionConstructor();
            if ($count === 0) {
                return $resultCollection;
            }

            $collectionSize = $collection->size();
            $expectedCount = $count;

            if ($collectionSize < $expectedCount) {
                return $resultCollection;
            }

            $rest = $collectionSize;
            $generated = 0;
            $multiplicity = (int)round($collectionSize / $expectedCount);

            $rangeRandomizer = static function () use ($multiplicity): int {
                return random_int(0, $multiplicity - 1);
            };

            $trier = static function () use (& $generated, & $rest, $expectedCount, $rangeRandomizer): bool {
                $rest--;
                if ($generated === $expectedCount) {
                    return false;
                }
                if ($generated + $rest + 1 <= $expectedCount) {
                    return true;
                }
                if ($rangeRandomizer() !== 0) {
                    return false;
                }
                $generated++;
                return true;
            };

            return $collection
                ->stream()
                ->filter($trier)
                ->getCollection();
        };
    }

    /**
     * Returns Closure that split collection into sub collections with $size
     * @param int $size
     * @return Closure
     */
    public static function chunk(int $size): Closure
    {
        return static function (Collection $collection) use ($size): Collection {
            $res = self::collectionConstructor();
            $currentChunk = [];

            return $res;
        };
    }

    /**
     * Returns Closure that collapses a collection of arrays into a single, flat collection
     * @return Closure
     */
    public static function collapse(): Closure
    {
        return static function (Collection $collection): Collection {
        };
    }

    /**
     * Returns Closure that returns a collection of elements that are not present in the given arguments
     * @param mixed ...$args
     * @return Closure
     */
    public static function diff(...$args): Closure
    {

    }

    /**
     * Returns Closure that returns a collection of elements that are present in the given arguments
     * @param mixed ...$args
     * @return Closure
     */
    public static function intersect(...$args): Closure
    {

    }

    /**
     * Returns Closure that returns new collection without elements that present in $collection
     * @param Collection $collection
     * @return Closure
     */
    public static function remove(Collection $collection): Closure
    {

    }

    public static function when(bool $condition, callable $reorganizer): Closure
    {
        if (!$condition) {
            return static function (Collection $collection) {
                return $collection;
            };
        }

        return $reorganizer;
    }
}
