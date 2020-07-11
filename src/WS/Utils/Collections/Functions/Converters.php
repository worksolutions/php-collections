<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;

class Converters
{

    /**
     * Returns function <Fn($obj: object): mixed>
     * @param string $name
     * @return Closure
     */
    public static function toPropertyValue(string $name): Closure
    {
        return static function ($obj) use ($name) {
            return ObjectFunctions::getPropertyValue($obj, $name);
        };
    }

    /**
     * Returns function <Fn($obj: object): array> that returns assoc array ['fieldName1' => 'value', 'fieldName2' => 'value2']
     * @param array $names
     * @return Closure
     */
    public static function toProperties(array $names): Closure
    {
        return static function ($obj) use ($names) {
            $res = [];
            foreach ($names as $name) {
                $res[$name] = ObjectFunctions::getPropertyValue($obj, $name);
            }

            return $res;
        };
    }
}
