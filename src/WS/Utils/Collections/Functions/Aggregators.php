<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use WS\Utils\Collections\ArrayList;
use WS\Utils\Collections\Collection;

class Aggregators
{
    public static function strImplode(string $delimiter = ''): callable
    {
        return static function (Collection $collection) use ($delimiter) {
            return implode($delimiter, $collection->toArray());
        };
    }

    public static function shuffle(): callable
    {
        return static function (Collection $collection): Collection {
            $array = $collection->toArray();
            shuffle($array);

            return new ArrayList($array);
        };
    }
}
