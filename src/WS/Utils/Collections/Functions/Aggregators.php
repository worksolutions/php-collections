<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use WS\Utils\Collections\Collection;

class Aggregators
{
    public static function strImplode(string $delimiter = ''): callable
    {
        return static function (Collection $collection) use ($delimiter) {
            return implode($delimiter, $collection->toArray());
        };
    }
}
