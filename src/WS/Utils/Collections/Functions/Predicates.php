<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Predicates
{
    public static function notNull(): callable
    {
        return static function ($el): bool {
            return $el !== null;
        };
    }

    public static function notResistance(): callable
    {
        return static function (): bool {
            return true;
        };
    }

    public static function lock(): callable
    {
        return static function (): bool {
            return false;
        };
    }

    public static function where($property, $value): callable
    {

    }
}
