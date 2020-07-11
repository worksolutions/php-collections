<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

class Consumers
{
    /**
     * Return function <Fn($el: mixed)> that dumps element
     * @return callable
     */
    public static function dump(): callable
    {
        return static function ($el) {
            var_dump($el);
        };
    }
}
