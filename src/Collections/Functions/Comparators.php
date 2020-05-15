<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Comparators
{
    public static function numericComparator(): callable
    {
        return static function ($a, $b) {
            return $a <=> $b;
        };
    }
}
