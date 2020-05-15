<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

interface Stream
{
    /**
     * Call function for each element in collection
     * @param callable $f
     * @return Stream
     */
    public function each(callable $f): Stream;

    /**
     * Filter elements with predicate function
     * @param callable $predicate
     * @return Stream
     */
    public function filter(callable $predicate): Stream;

    /**
     * Returns true if all elements in collection tested with predicate
     * @param callable $predicate
     * @return bool
     */
    public function allMatch(callable $predicate): bool;

    /**
     * Returns true if at least has noe element tested with predicate
     * @param callable $predicate
     * @return bool
     */
    public function anyMatch(callable $predicate): bool;

    /**
     * Converts all collection elements with converter
     * @param callable $converter
     * @return Stream
     */
    public function map(callable $converter): Stream;

    /**
     * Call aggregator function for collection. Is terminate function
     * @param callable $aggregator
     * @return mixed
     */
    public function aggregate(callable $aggregator);

    /**
     * Returns any elements from collection
     * @return mixed
     */
    public function findAny();

    /**
     * Returns first collection element
     * @return mixed
     */
    public function findFirst();

    /**
     * Returns min element from collection
     * @param callable $comparator
     * @return mixed
     */
    public function min(callable $comparator);

    /**
     * Returns max element from collection
     * @param callable $comparator
     * @return mixed
     */
    public function max(callable $comparator);

    /**
     * Reduce collection to single value with accumulator
     * @param callable $accumulator
     * @return mixed
     */
    public function reduce(callable $accumulator);

    /**
     * Use stream in parallel manner
     * @return mixed
     */
    public function parallel(): Stream;

    /**
     * Initialize collection with params
     * @param mixed|Collection ...$elements
     * @return mixed
     */
    public static function of(...$elements): Collection;
}
