<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

interface Queue extends Collection
{
    /**
     * Inserts the specified element into this queue if it is possible
     * @param $element
     * @return mixed
     */
    public function offer($element);

    /**
     * Retrieves and removes the head of this queue, or returns null if this queue is empty
     * @return mixed
     */
    public function poll();

    /**
     * Retrieves, but does not remove
     * @return mixed
     */
    public function peek();
}
