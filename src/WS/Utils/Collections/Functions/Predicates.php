<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Predicates
{
    public static function notNull(): callable
    {
        return static function ($el) {
            return $el !== null;
        };
    }
}
