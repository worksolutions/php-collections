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

    private static function fCountAggregator(): callable
    {
        return static function (int $item, ?int $accumulate) {
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

    public static function fInvokeCounter(): InvokesCounter
    {
        return new InvokesCounter();
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
     * @param $result
     */
    public function filterChecking($input, $filter, $result): void
    {
        $initData = $this->createCollection($input);
        $resultCollection = $initData->stream()
            ->filter($filter)
            ->getCollection();

        $this->assertTrue($resultCollection->equals(new ArrayList($result)));
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
     * @param $count
     */
    public function iterating($input, InvokesCounter $counter, $count)
    {
        $this->createCollection($input)
            ->stream()
            ->each($counter);
        ;
        $this->assertCount($count, $counter->calls());
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
     * @param $result
     */
    public function fullMatchingChecking($input, $checker, $result): void
    {
        $collection = $this->createCollection($input);
        $actualResult = $collection
            ->stream()
            ->allMatch($checker);

        $this->assertEquals($result, $actualResult);
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
     * @param $result
     */
    public function anyMatchingChecking($input, $checker, $result): void
    {
        $collection = $this->createCollection($input);
        $actualResult = $collection
            ->stream()
            ->anyMatch($checker);

        $this->assertEquals($actualResult, $result);
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
     * @param $result
     */
    public function mapConvertingChecking($input, $modifier, $result): void
    {
        $collection = $this->createCollection($input);
        $actualCollection = $collection->stream()
            ->map($modifier)
            ->getCollection();

        $this->assertThat($actualCollection, CollectionIsEqual::to(new ArrayList($result)));
    }

    public function aggregatorCases(): array
    {
        return [
            [[1,2,3], self::fSumAggregator(), 6],
            [[1, 2, 4], self::fCountAggregator(), 3],
            [[], self::fSumAggregator(), 0]
        ];
    }

    /**
     * @dataProvider aggregatorCases
     * @test
     * @param $input
     * @param $aggregator
     * @param $expected
     */
    public function aggregationChecking($input, $aggregator, $expected): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->aggregate($aggregator);

        $this->assertEquals($expected, $actual);
    }
}
