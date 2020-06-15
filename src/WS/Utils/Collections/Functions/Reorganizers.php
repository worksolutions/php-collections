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

    public static function random($count = 1): Closure
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
}
