<?php
/**
 * @author Igor Pomiluyko
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;

class ParallelStreamTest extends TestCase
{

    public function createCollection(array $els): Collection
    {
        return new ArrayList($els);
    }

    public static function fSquare(): callable
    {
        return static function (int $number): int {
            return $number ** 2;
        };
    }

    public static function fGreaterThanTen(): callable
    {
        return static function (int $number): bool {
            return $number > 10;
        };
    }

    public function mapCases()
    {
        return [
            [2, [], self::fSquare(), []],
            [null, [1, 2,], self::fSquare(), [1, 4,]],
            [5, [1, 2,], self::fSquare(), [1, 4,]],
            [4, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10], self::fSquare(), [1, 4, 9, 16, 25, 36, 49, 64, 81, 100]],
        ];
    }

    /**
     * @dataProvider mapCases
     * @test
     * @param $workersCount
     * @param $input
     * @param $fn
     * @param $expected
     */
    public function parallelMap($workersCount, $input, $fn, $expected)
    {
        $resultCollection = $this->createCollection($input)
            ->parallelStream($workersCount)
            ->map($fn)
            ->getCollection();

        self::assertTrue($resultCollection->equals($this->createCollection($expected)));
    }


    public function filterCases()
    {
        return [
            [1, [], self::fGreaterThanTen(), []],
            [null, [1], self::fGreaterThanTen(), []],
            [3, [1, 2, 3, 4], self::fGreaterThanTen(), []],
            [4, [1, 2, 3, 4, 10, 11, 2], self::fGreaterThanTen(), [11]],
            [2, [11, 2, -19], self::fGreaterThanTen(), [11]],
            [2, [11, 12, 13], self::fGreaterThanTen(), [11, 12, 13]],
        ];
    }

    /**
     * @dataProvider filterCases
     * @test
     * @param $workersCount
     * @param $input
     * @param $fn
     * @param $expected
     */
    public function parallelFilter($workersCount, $input, $fn, $expected)
    {
        $resultCollection = $this->createCollection($input)
            ->parallelStream($workersCount)
            ->filter($fn)
            ->getCollection();

        self::assertTrue($resultCollection->equals($this->createCollection($expected)));
    }

}
