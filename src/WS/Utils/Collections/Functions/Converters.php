<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;

class Converters
{

    /**
     * @param string $name
     * @return Closure
     */
    public static function toPropertyValue(string $name): Closure
    {
        return static function ($obj) use ($name) {
            return ObjectFunctions::getPropertyValue($obj, $name);
        };
    }
}
