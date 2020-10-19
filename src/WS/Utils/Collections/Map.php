<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use IteratorAggregate;

interface Map extends IteratorAggregate
{
    public function put($key, $value): bool;

    public function keys(): Collection;

    public function values(): Collection;

    /**
     * Removes entry by key
     * @param mixed $key
     * @return bool
     */
    public function remove($key): bool;

    /**
     * @param mixed $key
     * @return bool
     */
    public function containsKey($key): bool;

    /**
     * Checks if map contains the value with strict comparision
     * @param mixed $value
     * @return bool
     */
    public function containsValue($value): bool;

    /**
     * Returns count of map pairs
     * @return int
     */
    public function size(): int;

    /**
     * Returns the value to which the specified key is mapped, or null if this map contains no mapping for the key.
     * @param $key
     * @return mixed|null
     */
    public function get($key);

    /**
     * Creates a stream with internal collection MapEntry objects
     * @return Stream Stream<MapEntry>
     */
    public function stream(): Stream;
}
