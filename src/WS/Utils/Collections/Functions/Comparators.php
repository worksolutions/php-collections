<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Comparators
{
    /**
     * Used for scalar value compares such as int, bool, float, string
     * @return callable
     */
    public static function scalarComparator(): callable
    {
        return static function ($a, $b) {
            return $a <=> $b;
        };
    }

    public static function objectPropertyComparator(string $property): callable
    {
        return static function ($a, $b) use ($property) {
            return ObjectFunctions::getPropertyValue($a, $property) <=> ObjectFunctions::getPropertyValue($b, $property);
        };
    }
}
