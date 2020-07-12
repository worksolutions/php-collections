<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

interface Stream
{
    /**
     * Call function for each element in collection
     * @param callable $consumer Function with f(mixed $element, int $index): void interface
     * @return Stream
     */
    public function each(callable $consumer): Stream;

    /**
     * Call function for $limit element in collection. If limit is null all elements will. If consumer will return false walk stop
     * @param callable $consumer Function with f(mixed $element, int $index): ?false|mixed interface.
     * @param int|null $limit
     * @return Stream
     */
    public function walk(callable $consumer, ?int $limit = null): Stream;

    /**
     * Filter elements with predicate function
     * @param callable $predicate Function with f(mixed $element): bool interface
     * @return Stream
     */
    public function filter(callable $predicate): Stream;

    /**
     * @param callable $reorganizer Function with f(Collection $c): Collection interface
     * @return Stream
     */
    public function reorganize(callable $reorganizer): Stream;

    /**
     * Returns true if all elements in collection tested with predicate
     * @param callable $predicate Function with f(mixed $element): bool interface
     * @return bool
     */
    public function allMatch(callable $predicate): bool;

    /**
     * Returns true if at least has noe element tested with predicate
     * @param callable $predicate Function with f(mixed $element): bool interface
     * @return bool
     */
    public function anyMatch(callable $predicate): bool;

    /**
     * Converts all collection elements with converter
     * @param callable $converter Function with f(mixed $element): mixed interface
     * @return Stream
     */
    public function map(callable $converter): Stream;

    /**
     * Call collector function for collection. It is terminate function
     * @param callable $collector Function f(Collection $c): mixed
     * @return mixed
     */
    public function collect(callable $collector);

    /**
     * Returns any elements from collection or null if absent
     * @return mixed
     */
    public function findAny();

    /**
     * Returns first collection element or null if absent
     * @return mixed
     */
    public function findFirst();

    /**
     * Returns last collection element or null if absent
     * @return mixed
     */
    public function findLast();

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
     * @param callable $comparator
     * @return mixed
     */
    public function sort(callable $comparator): Stream;

    /**
     * Sorts elements with value extractor via common scalar sort method
     * @param callable $extractor function for getting value  <f(mixed $el): scalar>
     * @return Stream
     */
    public function sortBy(callable $extractor): Stream;

    /**
     * @param callable $comparator
     * @return Stream
     */
    public function sortDesc(callable $comparator): Stream;

    /**
     * Sorts desc elements with value extractor via common scalar sort method
     * @param callable $extractor function for getting value <f(mixed $el): scalar>
     * @return Stream
     */
    public function sortByDesc(callable $extractor): Stream;

    /**
     * Placed elements in reverse order
     * @return Stream
     */
    public function reverse(): Stream;
    
    /**
     * Reduce collection to single value with accumulator
     * @param callable $accumulator
     * @return mixed
     */
    public function reduce(callable $accumulator);

    /**
     * Limits amount of stream collection elements
     * @param int $count
     * @return Stream
     */
    public function limit(int $count): Stream;

    /**
     * If condition is false stream became inert with stream functions and if next call will be true stream became operated again
     * @param bool $condition
     * @return Stream
     */
    public function when(bool $condition): Stream;

    /**
     * Returns collection
     * @return Collection
     */
    public function getCollection(): Collection;

    /**
     * Clear current condition. The same as when(true)
     * @return Stream
     */
    public function always(): Stream;
}
