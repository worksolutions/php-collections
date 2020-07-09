<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Iterator;

class IteratorFactory
{
    public static function directSequence($length): Iterator
    {
        $current = 0;
        return new CallbackIterator(static function () use (& $current, $length) {
            if ($current === $length) {
                return (new IterateResult())->setAsRunOut();
            }

            return (new IterateResult())->setValue($current++);
        });
    }

    public static function reverseSequence($length): Iterator
    {
        $current = $length - 1;
        return new CallbackIterator(static function () use (& $current) {
            if ($current === -1) {
                return (new IterateResult())->setAsRunOut();
            }

            return (new IterateResult())->setValue($current--);
        });
    }
}
