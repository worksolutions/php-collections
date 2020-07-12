<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;

class Comparators
{
    /**
     * Returns <Fn($a: scalar, $b: scalar): int> for scalar value compares such as int, bool, float, string
     * @return callable
     */
    public static function scalarComparator(): Closure
    {
        return static function ($a, $b) {
            return $a <=> $b;
        };
    }

    /**
     * Returns <Fn($a: object, $b: object): int>  for scalar object property value compares such as int, bool, float, string
     * @param string $property
     * @return callable
     */
    public static function objectPropertyComparator(string $property): Closure
    {
        return static function ($a, $b) use ($property) {
            return ObjectFunctions::getPropertyValue($a, $property) <=> ObjectFunctions::getPropertyValue($b, $property);
        };
    }

    /**
     * Returns <Fn($a: scalar, $b: scalar): int> for scalar value compares such as int, bool, float, string
     * @param callable $f <Fn(value: mixed): scalar>
     * @return Closure
     */
    public static function callbackComparator(callable $f): Closure
    {
        return static function ($a, $b) use ($f) {
            return $f($a) <=> $f($b);
        };
    }
}
