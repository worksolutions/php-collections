<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Comparators
{
    /**
     * Return <Fn($a: scalar, $b: scalar): int> for scalar value compares such as int, bool, float, string
     * @return callable
     */
    public static function scalarComparator(): callable
    {
        return static function ($a, $b) {
            return $a <=> $b;
        };
    }

    /**
     * Return <Fn($a: object, $b: object): int>  for scalar object property value compares such as int, bool, float, string
     * @param string $property
     * @return callable
     */
    public static function objectPropertyComparator(string $property): callable
    {
        return static function ($a, $b) use ($property) {
            return ObjectFunctions::getPropertyValue($a, $property) <=> ObjectFunctions::getPropertyValue($b, $property);
        };
    }
}
