<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Iterator;

interface Iterator
{
    /**
     * Returns next int value or RuntimeException then run it out
     * @return mixed
     */
    public function next();

    /**
     * Return presents sign of elements in iterator
     * @return bool
     */
    public function hasNext(): bool;
}
