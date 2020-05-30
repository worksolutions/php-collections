<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

interface Stack extends Collection
{
    /**
     * Adds element to the top of stack
     * @param $element
     * @return bool
     */
    public function push($element): bool;

    /**
     * Gets element from the top of stack
     * @return mixed
     */
    public function pop();

    /**
     * Retrieves, but does not remove
     * @return mixed
     */
    public function peek();
}
