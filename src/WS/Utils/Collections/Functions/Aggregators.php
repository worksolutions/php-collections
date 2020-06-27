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
     * Returns closure for getting map<value, int repeats>. keys - collection uniq values, values - count of repeats
     * @return Closure
     */
    public static function group(): Closure
    {
        return static function (Collection $collection): Map {
            $groupBy = self::groupBy(static function ($el) {
                return $el;
            });
            return $groupBy($collection);
        };
    }

    /**
     * Returns closure for getting map. keys - collection uniq fieldName values, values - collection of objects
     * @param string $property
     * @return Closure
     */
    public static function groupByProperty(string $property): Closure
    {
        return static function (Collection $collection) use ($property): Map {
            $fGetValue = static function ($obj) use ($property) {
                return ObjectFunctions::getPropertyValue($obj, $property);
            };

            $groupBy = self::groupBy($fGetValue);
            return $groupBy($collection);
        };
    }

    public static function groupBy(callable $f): Closure
    {
        return static function (Collection $collection) use ($f): Map {
            $group = new HashMap();
            $collection
                ->stream()
                ->each(static function ($el) use ($group, $f) {
                    $value = $f($el);
                    $count = 0;
                    if (($gCount = $group->get($value)) !== null) {
                        $count = $gCount;
                    }
                    $group->put($value, $count + 1);
                });
            return $group;
        };
    }
}
