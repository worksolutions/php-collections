<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use Closure;
use WS\Utils\Collections\HashSet;

class Predicates
{

    /**
     * Returns <Fn($el: mixed): bool> blocked all tries
     * @return Closure
     */
    public static function lock(): Closure
    {
        return static function (): bool {
            return false;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed all not null elements
     * @return Closure
     */
    public static function notNull(): Closure
    {
        return static function ($el): bool {
            return $el !== null;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed all tries
     * @return Closure
     */
    public static function notResistance(): Closure
    {
        return static function (): bool {
            return true;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed each even element of call
     * @return Closure
     */
    public static function eachEven(): Closure
    {
        $isEven = false;
        return static function () use (& $isEven) {
            $res = $isEven;
            $isEven = !$isEven;

            return $res;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed each nth element of call
     * @param $number
     * @return Closure
     */
    public static function nth($number): Closure
    {
        $counter = 0;
        return static function () use ($number, & $counter) {
            $res = ++$counter % $number === 0;
            if ($res) {
                $counter = 0;
            }
            return $res;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with equal value
     * @param $value
     * @return Closure
     */
    public static function equal($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el === $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed unique element at once
     */
    public static function lockDuplicated(): Closure
    {
        $set = new HashSet();
        return static function ($el) use ($set): bool {
            $res = !$set->contains($el);
            $set->add($el);
            return $res;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with less than value comparing
     * @param $value
     * @return Closure
     */
    public static function lessThan($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el < $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with less or equal value comparing
     * @param $value
     * @return Closure
     */
    public static function lessOrEqual($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el <= $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with more than value comparing
     * @param $value
     * @return Closure
     */
    public static function moreThan($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el > $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with more or equal value comparing
     * @param $value
     * @return Closure
     */
    public static function moreOrEqual($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el >= $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with not value comparing
     * @param $value
     * @return Closure
     */
    public static function not($value): Closure
    {
        return static function ($el) use ($value): bool {
            return $el !== $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with include of set value comparing
     * @param array $values
     * @return Closure
     */
    public static function in(array $values): Closure
    {
        return static function ($el) use ($values): bool {
            return in_array($el, $values, true);
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with not include of set value comparing
     * @param array $values
     * @return Closure
     */
    public static function notIn(array $values): Closure
    {
        return static function ($el) use ($values): bool {
            return !in_array($el, $values, true);
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with where object property value comparing
     * @param string $property
     * @param $value
     * @return Closure
     */
    public static function where(string $property, $value): Closure
    {
        return static function ($ob) use ($property, $value) {
            return $value === ObjectFunctions::getPropertyValue($ob, $property);
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with where not object property value comparing
     * @param string $property
     * @param $value
     * @return Closure
     */
    public static function whereNot(string $property, $value): Closure
    {
        return static function ($ob) use ($property, $value) {
            return $value !== ObjectFunctions::getPropertyValue($ob, $property);
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with where present in a set of object property value comparing
     * @param string $property
     * @param array $values
     * @return Closure
     */
    public static function whereIn(string $property, array $values): Closure
    {
        return static function ($ob) use ($property, $values) {
            return in_array(ObjectFunctions::getPropertyValue($ob, $property), $values, true);
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with where not present in a set of object property value comparing
     * @param string $property
     * @param array $values
     * @return Closure
     */
    public static function whereNotIn(string $property, array $values): Closure
    {
        return static function ($ob) use ($property, $values) {
            return !in_array(ObjectFunctions::getPropertyValue($ob, $property), $values, true);
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with more than object property value comparing
     * @param string $property
     * @param $value
     * @return Closure
     */
    public static function whereMoreThan(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) > $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with less than object property value comparing
     * @param string $property
     * @param $value
     * @return Closure
     */
    public static function whereLessThan(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) < $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with more or equal object property value comparing
     * @param string $property
     * @param $value
     * @return Closure
     */
    public static function whereMoreOrEqual(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) >= $value;
        };
    }

    /**
     * Returns <Fn($el: mixed): bool> passed with less or equal object property value comparing
     * @param string $property
     * @param $value
     * @return Closure
     */
    public static function whereLessOrEqual(string $property, $value): Closure
    {
        return static function (object $ob) use ($property, $value) {
            return ObjectFunctions::getPropertyValue($ob, $property) <= $value;
        };
    }
}
