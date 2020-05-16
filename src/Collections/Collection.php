<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use IteratorAggregate;

interface Collection extends IteratorAggregate
{
    /**
     * Ensures that this collection contains the specified element.
     * @param $element
     * @return bool
     */
    public function add($element): bool;

    /**
     * Merge the elements in the specified collection to this collection
     * @param Collection $collection
     * @return bool
     */
    public function merge(Collection $collection): bool;

    /**
     * Removes all of the elements from this collection
     */
    public function clear(): void;

    /**
     * Removes a single instance of the specified element from this collection, if it is present
     * @param $element
     * @return bool
     */
    public function remove($element): bool;

    /**
     * Returns true if this collection contains the specified element
     * @param $element
     * @return bool
     */
    public function contains($element): bool;

    /**
     * Compares collections
     * @param Collection $collection
     * @return bool
     */
    public function equals(Collection $collection): bool;

    /**
     * Returns size of collection elements
     * @return int
     */
    public function size(): int;

    /**
     * Returns true if this collection contains no elements
     */
    public function isEmpty(): bool;

    /**
     * Returns a Stream with this collection as its source
     */
    public function stream(): Stream;

    /**
     * Returns an array containing all of the elements in this collection
     */
    public function toArray(): array;
}
