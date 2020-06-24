<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Consumers
{
    public static function dump(): callable
    {
        return static function ($el) {
            var_dump($el);
        };
    }
}
