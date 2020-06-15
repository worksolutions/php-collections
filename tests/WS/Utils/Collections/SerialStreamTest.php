<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\InvokesCounter;

class SerialStreamTest extends TestCase
{

    /** @noinspection PhpUnusedParameterInspection */
    private static function fCountAggregator(): callable
    {
        return static function (int $_, ?int $accumulate) {
            $accumulate = $accumulate ?? 0;

            return ++$accumulate;
        };
    }

    private static function fSumAggregator(): callable
    {
        return static function (int $item, ?int $accumulate) {
            $accumulate = $accumulate ?? 0;

            return $item + $accumulate;
        };
    }

    public function createCollection(array $els): Collection
    {
        return new ArrayList($els);
    }

    public static function fMoreThanTen(): callable
    {
        return static function (int $number):bool {
            return $number > 10;
        };
    }

    public static function fIncrement(): callable
    {
        return static function (int $number): int {
            return $number + 1;
        };
    }

    public static function fImplode(string $glue): callable
    {
        return static function (Collection $pieces) use ($glue){
            return implode($glue, $pieces->toArray());
        };
    }

    public static function fInvokeCounter(): InvokesCounter
    {
        return new InvokesCounter();
    }

    public static function fIntComparator(): callable
    {
        return static function ($a, $b) {
            return $a <=> $b;
        };
    }

    public function filteringCases(): array
    {
        return [
            [[], self::fMoreThanTen(), []],
            [[1], self::fMoreThanTen(), []],
            [[1, 2, 3, 4], self::fMoreThanTen(), []],
            [[1, 2, 3, 4, 10, 11, 2], self::fMoreThanTen(), [11]],
            [[11, 2, -19], self::fMoreThanTen(), [11]],
            [[11, 12, 13], self::fMoreThanTen(), [11, 12, 13]],
        ];
    }

    /**
     * @dataProvider filteringCases
     * @test
     * @param $input
     * @param $filter
     * @param $expected
     */
    public function filterChecking($input, $filter, $expected): void
    {
        $initData = $this->createCollection($input);
        $resultCollection = $initData->stream()
            ->filter($filter)
            ->getCollection();

        $this->assertTrue($resultCollection->equals(new ArrayList($expected)));
    }

    public function iteratingCases(): array
    {
        return [
            [[1, 2, 3], self::fInvokeCounter(), 3],
            [[], self::fInvokeCounter(), 0],
            [[0, 0 , 0], self::fInvokeCounter(), 3],
        ];
    }

    /**
     * @dataProvider iteratingCases
     * @test
     * @param $input
     * @param InvokesCounter $counter
     * @param $expectedCount
     */
    public function iterating($input, InvokesCounter $counter, $expectedCount): void
    {
        $this->createCollection($input)
            ->stream()
            ->each($counter);
        $this->assertCount($expectedCount, $counter->calls());
    }

    public function allMatchingCases(): array
    {
        return [
            [[1, 2], self::fMoreThanTen(), false],
            [[11, 12], self::fMoreThanTen(), true],
            [[], self::fMoreThanTen(), true],
        ];
    }

    /**
     * @dataProvider allMatchingCases
     * @test
     * @param $input
     * @param $checker
     * @param $expected
     */
    public function fullMatchingChecking($input, $checker, $expected): void
    {
        $collection = $this->createCollection($input);
        $actualResult = $collection
            ->stream()
            ->allMatch($checker);

        $this->assertEquals($expected, $actualResult);
    }

    public function anyMatchingCases(): array
    {
        return [
            [[1,2,3], self::fMoreThanTen(), false],
            [[1,12,3], self::fMoreThanTen(), true],
            [[], self::fMoreThanTen(), false],
            [[11, 12], self::fMoreThanTen(), true],
            [[-11, -12], self::fMoreThanTen(), false],
        ];
    }

    /**
     * @dataProvider anyMatchingCases
     * @test
     * @param $input
     * @param $checker
     * @param $expected
     */
    public function anyMatchingChecking($input, $checker, $expected): void
    {
        $collection = $this->createCollection($input);
        $actualResult = $collection
            ->stream()
            ->anyMatch($checker);

        $this->assertEquals($actualResult, $expected);
    }

    public function mapConvertingCases(): array
    {
        return [
            [[1, 2, 3], self::fIncrement(), [2, 3, 4]],
            [[], self::fIncrement(), []],
            [[0, 0, 0], self::fIncrement(), [1, 1, 1]],
        ];
    }

    /**
     * @dataProvider mapConvertingCases
     * @test
     * @param $input
     * @param $modifier
     * @param $expected
     */
    public function mapConvertingChecking($input, $modifier, $expected): void
    {
        $collection = $this->createCollection($input);
        $actualCollection = $collection->stream()
            ->map($modifier)
            ->getCollection();

        $this->assertThat($actualCollection, CollectionIsEqual::to(new ArrayList($expected)));
    }

    public function reduceCases(): array
    {
        return [
            [[1,2,3], self::fSumAggregator(), 6],
            [[1, 2, 4], self::fCountAggregator(), 3],
            [[], self::fSumAggregator(), 0]
        ];
    }

    /**
     * @dataProvider reduceCases
     * @test
     * @param $input
     * @param $accumulator
     * @param $expected
     */
    public function reduceChecking($input, $accumulator, $expected): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->reduce($accumulator);

        $this->assertEquals($expected, $actual);
    }

    public function aggregateCases(): array
    {
        return [
            [[1, 2, 3], self::fImplode(''), '123'],
            [['-'], self::fImplode('|'), '-'],
            [[], self::fImplode('|'), ''],
        ];
    }

    /**
     * @dataProvider aggregateCases
     * @test
     * @param $input
     * @param $aggregator
     * @param $expected
     */
    public function aggregateRightChecking($input, $aggregator, $expected): void
    {
        $collection = $this->createCollection($input);
        $actual = $collection
            ->stream()
            ->aggregate($aggregator);

        $this->assertEquals($expected, $actual);
    }

    public function setOfArrays(): array
    {
        return [
            [[1, 2, 3, 4, 5, 6]],
            [[]],
            [['1', 2, '2', '4']],
            [[1, 10, 9, 8]]
        ];
    }

    /**
     * @dataProvider setOfArrays
     * @test
     * @param $input
     */
    public function findAnyElementChecking($input): void
    {
        $el = $this->createCollection($input)
            ->stream()
            ->findAny();

        if ($input === []) {
            $this->assertNull($el);
            return ;
        }
        $this->assertContains($el, $input);
    }

    public function sortDataSet(): array
    {
        return [
            // input   | comparator             | min| max | sorted
            [[1, 2, 3, 4], self::fIntComparator(), 1, 4, [1, 2, 3, 4]],
            [[3, 12, 1, 4], self::fIntComparator(), 1, 12, [1, 3, 4, 12]],
            [[], self::fIntComparator(), null, null, []],
            [[1], self::fIntComparator(), 1, 1, [1]],
        ];
    }

    /**
     * @dataProvider sortDataSet
     * @test
     * @param $input
     * @param $comparator
     * @param $expected
     */
    public function minPickChecking($input, $comparator, $expected): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->min($comparator);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider sortDataSet
     * @test
     * @param $input
     * @param $comparator
     * @param $_
     * @param $expected
     * @noinspection PhpUnusedParameterInspection
     */
    public function maxPickChecking($input, $comparator, $_, $expected): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->max($comparator);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider sortDataSet
     * @test
     * @param $input
     * @param $comparator
     * @param $_1
     * @param $_2
     * @param $expected
     * @noinspection PhpUnusedParameterInspection
     */
    public function sortChecking($input, $comparator, $_1, $_2, $expected): void
    {
        $actual = $this
            ->createCollection($input)
            ->stream()
            ->sort($comparator)
            ->getCollection()
            ->toArray();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider sortDataSet
     * @test
     * @param $input
     * @param $comparator
     * @param $_1
     * @param $_2
     * @param $expected
     * @noinspection PhpUnusedParameterInspection
     */
    public function sortDescChecking($input, $comparator, $_1, $_2, $expected): void
    {
        $actual = $this
            ->createCollection($input)
            ->stream()
            ->sortDesc($comparator)
            ->getCollection()
            ->toArray();

        $this->assertEquals(array_reverse($expected), $actual);
    }
}
