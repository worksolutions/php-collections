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
    private static function collectionConstructor(? array $elements = null): Collection  {
        return new ArrayList($elements);
    }

    /**
     * Returns <Fn($c: Collection): Collection> that shuffles elements
     * @return Closure
     */
    public static function shuffle(): Closure
    {
        return static function (Collection $collection): Collection {
            $array = $collection->toArray();
            shuffle($array);

            return self::collectionConstructor($array);
        };
    }

    /**
     * Returns Closure <Fn($c: Collection): Collection> that gets $count random elements from collection
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
     * Returns Closure <Fn($c: Collection): Collection> that split collection into sub collections with $size
     * @param int $size
     * @return Closure
     */
    public static function chunk(int $size): Closure
    {
        return static function (Collection $collection) use ($size): Collection {
            $chunkCollection = self::collectionConstructor();
            $currentChunk = self::collectionConstructor();
            $pointer = $size;
            $collection
                ->stream()
                ->each(static function ($el) use ($size, $chunkCollection, & $currentChunk, & $pointer) {
                    $pointer--;
                    $currentChunk->add($el);

                    if ($pointer === 0) {
                        $chunkCollection->add($currentChunk);
                        $currentChunk = self::collectionConstructor();
                        $pointer = $size;
                    }
                })
            ;
            return $chunkCollection;
        };
    }

    /**
     * Returns Closure <Fn($c: Collection): Collection> that collapses a collection of arrays into a single, flat collection
     * @param int $depth Depth of  collapses. The 0 value is without
     * @return Closure
     */
    public static function collapse(int $depth = 0): Closure
    {
        if ($depth === 0) {
            $depth = null;
        }
        return static function (Collection $collection) use ($depth): Collection {
            $flatIterable = static function (iterable $collection, $depth) use (& $flatIterable): array  {
                $goToDepth = $depth > 0 || $depth === null;
                if ($depth !== null) {
                    $depth--;
                }

                $res = [];
                foreach ($collection as $item) {
                    if (is_iterable($item) && $goToDepth) {
                        $toPush = $flatIterable($item, $depth);
                        array_unshift($toPush, $res);
                        array_push(...$toPush);
                        $res = array_shift($toPush);
                    } else {
                        $res[] = $item;
                    }
                }
                return $res;
            };

            return self::collectionConstructor($flatIterable($collection, $depth));
        };
    }
}
