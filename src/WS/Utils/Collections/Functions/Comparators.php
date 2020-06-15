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

    public static function objectFieldComparator(string $fieldName): callable
    {
        return static function ($a, $b) use ($fieldName) {
            return ObjectFunctions::getFieldValue($a, $fieldName) <=> ObjectFunctions::getFieldValue($b, $fieldName);
        };
    }
}
