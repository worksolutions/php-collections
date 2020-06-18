<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

interface ListSequence extends Collection
{
    /**
     * Returns the element at the specified position in this list
     * @param int $index
     * @return mixed
     */
    public function get(int $index);

    /**
     * Replaces the element at the specified position in this list with the specified element
     * @param $el
     * @param int $index
     * @return mixed
     */
    public function set($el, int $index);

    /**
     * Returns index of specified element
     * @param $el
     * @return mixed
     */
    public function indexOf($el);

    /**
     * Returns the index of the last occurrence of the specified element in this list,
     * or FALSE if this list does not contain the element
     * @param $el
     * @return mixed
     */
    public function lastIndexOf($el);

    /**
     * Removes the element at the specified position in this list
     * @param int $index
     * @return mixed
     */
    public function removeAt(int $index);
}
