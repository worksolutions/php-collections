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
     * Replaces the element at the specified position in this list with the specified element. If $index is more than elements count OutOfRangeException will occur
     * @param $element
     * @param int $index
     * @return mixed
     */
    public function set($element, int $index);

    /**
     * Returns index of specified element of NULL if element is absent
     * @param $element
     * @return mixed
     */
    public function indexOf($element): ?int;

    /**
     * Returns the index of the last occurrence of the specified element in this list,
     * or NULL if this list does not contain the element
     * @param $element
     * @return mixed
     */
    public function lastIndexOf($element): ?int;

    /**
     * Removes the element at the specified position in this list
     * @param int $index
     * @return mixed
     */
    public function removeAt(int $index);
}
