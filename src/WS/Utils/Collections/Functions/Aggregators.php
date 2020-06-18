<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;
use WS\Utils\Collections\Collection;
use WS\Utils\Collections\HashMap;
use WS\Utils\Collections\Map;

/**
 * Class Aggregators
 * This class consist of static methods with <f(...$args): Closure<f(Collection $c): mixed>>
 * @package WS\Utils\Collections\Functions
 */
class Aggregators
{
    public static function strImplode(string $delimiter = ''): Closure
    {
        return static function (Collection $collection) use ($delimiter) {
            return implode($delimiter, $collection->toArray());
        };
    }

    /**
     * Returns closure for getting average collection value
     * @return Closure
     */
    public static function average(): Closure
    {
        /**
         * @param Collection $collection
         * @return float|int
         */
        return static function (Collection $collection) {
            $array = $collection->toArray();

            return array_sum($array) / count($array);
        };
    }

    /**
     * Returns closure for getting map. keys - collection uniq values, values - count of repeats
     * @return Closure
     */
    public static function countByValue(): Closure
    {
        return static function (Collection $collection): Map {
            return new HashMap();
        };
    }

    /**
     * Returns closure for getting map. keys - collection uniq fieldName values, values - collection of objects
     * @param string $fieldName
     * @return Closure
     */
    public static function groupByObjectField(string $fieldName): Closure
    {
        return static function (Collection $collection) use ($fieldName): Map {
            return new HashMap();
        };
    }


}
