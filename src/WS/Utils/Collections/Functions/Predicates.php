<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;

class Predicates
{

    public static function lock(): Closure
    {
        return static function (): bool {
            return false;
        };
    }

    public static function notNull(): Closure
    {
        return static function ($el): bool {
            return $el !== null;
        };
    }

    public static function notResistance(): Closure
    {
        return static function (): bool {
            return true;
        };
    }

    public static function equal($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el === $value;
        };
    }

    public static function lessThan($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el < $value;
        };
    }

    public static function lessOrEqual($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el <= $value;
        };
    }

    public static function moreThan($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el > $value;
        };
    }

    public static function moreOrEqual($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el >= $value;
        };
    }

    public static function not($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el !== $value;
        };
    }

    public static function in(array $values): Closure
    {
        return static function ($el) use ($values): bool {
            return in_array($el, $values, true);
        };
    }

    public static function notIn(array $values): Closure
    {
        return static function ($el) use ($values): bool {
            return !in_array($el, $values, true);
        };
    }

    public static function where(string $property, $value): Closure
    {
        return static function ($ob) use ($property, $value) {
            return $value === ObjectFunctions::getPropertyValue($ob, $property);
        };
    }

    public static function whereNot(string $property, $value): Closure
    {
        return static function ($ob) use ($property, $value) {
            return $value !== ObjectFunctions::getPropertyValue($ob, $property);
        };
    }

    public static function whereIn(string $property, array $values): Closure
    {
        return static function ($ob) use ($property, $values) {
            return in_array(ObjectFunctions::getPropertyValue($ob, $property), $values, true);
        };
    }

    public static function whereNotIn(string $property, array $values): Closure
    {
        return static function ($ob) use ($property, $values) {
            return !in_array(ObjectFunctions::getPropertyValue($ob, $property), $values, true);
        };
    }

    public static function whereMoreThan(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) > $value;
        };
    }

    public static function whereLessThan(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) < $value;
        };
    }

    public static function whereMoreOrEqual(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) >= $value;
        };
    }

    public static function whereLessOrEqual(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) <= $value;
        };
    }
}
