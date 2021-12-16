<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use Exception;
use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\ExampleObject;
use WS\Utils\Collections\Utils\InvokeCounter;
use WS\Utils\Collections\Utils\TestInteger;

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

    private static function fEmptyFunction(): callable
    {
        return static function () {};
    }

    public function createCollection(array $els): Collection
    {
        return new ArrayList($els);
    }

    public static function fGreaterThanTen(): callable
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

    public static function fInvokeCounter(): InvokeCounter
    {
        return new InvokeCounter();
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
            [[], self::fGreaterThanTen(), []],
            [[1], self::fGreaterThanTen(), []],
            [[1, 2, 3, 4], self::fGreaterThanTen(), []],
            [[1, 2, 3, 4, 10, 11, 2], self::fGreaterThanTen(), [11]],
            [[11, 2, -19], self::fGreaterThanTen(), [11]],
            [[11, 12, 13], self::fGreaterThanTen(), [11, 12, 13]],
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
     * @param InvokeCounter $counter
     * @param $expectedCount
     */
    public function iterating($input, InvokeCounter $counter, $expectedCount): void
    {
        $this->createCollection($input)
            ->stream()
            ->each($counter);
        $this->assertCount($expectedCount, $counter->calls());
    }

    public function allMatchingCases(): array
    {
        return [
            [[1, 2], self::fGreaterThanTen(), false],
            [[11, 12], self::fGreaterThanTen(), true],
            [[], self::fGreaterThanTen(), true],
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
            [[1,2,3], self::fGreaterThanTen(), false],
            [[1,12,3], self::fGreaterThanTen(), true],
            [[], self::fGreaterThanTen(), false],
            [[11, 12], self::fGreaterThanTen(), true],
            [[-11, -12], self::fGreaterThanTen(), false],
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
            [[1,2,3], self::fSumAggregator(), 4, 10],
            [[1, 2, 4], self::fCountAggregator(), 0, 3],
            [[], self::fSumAggregator(), null, null],
            [[], self::fEmptyFunction(), [], []]
        ];
    }

    /**
     * @dataProvider reduceCases
     * @test
     * @param $input
     * @param $accumulator
     * @param $initialValue
     * @param $expected
     */
    public function reduceChecking($input, $accumulator, $initialValue, $expected): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->reduce($accumulator, $initialValue);

        $this->assertSame($expected, $actual);
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
            ->collect($aggregator);

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
            // input       | comparator           | min  | max | sorted
            [[1, 2, 3, 4],  self::fIntComparator(), 1,    4,    [1, 2, 3, 4]],
            [[3, 12, 1, 4], self::fIntComparator(), 1,    12,   [1, 3, 4, 12]],
            [[],            self::fIntComparator(), null, null, []],
            [[1],           self::fIntComparator(), 1,    1,    [1]],
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

    /**
     * @return array
     */
    public function firstLastElementCases(): array
    {
        return [
            [ [1, 2, 3], 1, 3],
            [ [1], 1 ,1],
            [[], null, null]
        ];
    }

    public function firstFirstElementWithFilterCases(): array
    {
        return [
            [ [1, 2, 3], 2, 2],
            [ [1], 2, null],
            [[], 2, null]
        ];
    }

    /**
     * @dataProvider firstLastElementCases
     * @test
     * @param $input
     * @param $first
     */
    public function findFirstElement($input, $first): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->findFirst();
        $this->assertEquals($first, $actual);
    }

    /**
     * @dataProvider firstFirstElementWithFilterCases
     * @test
     * @param array $input
     * @param mixed $first
     * @param mixed $expected
     */
    public function findFirstElementWithFilter(array $input, $first, $expected): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->findFirst(function ($item) use ($first) {
                return $item === $first;
            });
        $this->assertEquals($expected, $actual);
    }

    /** @noinspection PhpUnusedParameterInspection */
    /**
     * @dataProvider firstLastElementCases
     * @test
     * @param $input
     * @param $_
     * @param $last
     */
    public function findLastElement($input, $_, $last): void
    {
        $actual = $this->createCollection($input)
            ->stream()
            ->findLast()
        ;
        $this->assertEquals($last, $actual);
    }

    public function sortCases(): array
    {
        return [
            [[1, 2, 4, 3], 1, 4],
            [[1], 1, 1],
            [[4, 5, 2, 2, 7], 2, 7],
            [[4, 5, 2, 2, 7], 2, 7],
        ];
    }

    /**
     * @dataProvider sortCases
     * @test
     * @param $input
     * @param $min
     * @param $max
     * @return void
     */
    public function sortingWithExtractor($input, $min, $max): void
    {
        $sortedCollection = $this->createCollection($input)
            ->stream()
            ->map(static function (int $num) {
                return TestInteger::of($num);
            })
            ->sortBy(static function (TestInteger $integer) {
                return $integer->getValue();
            })
            ->getCollection()
        ;

        $actualMin = $sortedCollection->stream()->findFirst();
        $actualMax = $sortedCollection->stream()->findLast();

        $this->assertEquals($min, $actualMin->getValue());
        $this->assertEquals($max, $actualMax->getValue());
    }

    /**
     * @test
     */
    public function sortingWithNotScalarValue(): void
    {
        $this->expectException(Exception::class);

        $this->createCollection([1, 2])
            ->stream()
            ->sortBy(static function () {
                return [];
            })
            ->getCollection()
        ;
    }

    /**
     * @test
     */
    public function sortingWithSingleValue(): void
    {
        $obj = new ExampleObject();
        $obj->property = 1.12;
        $sortedFirstElement = $this->createCollection([$obj])
            ->stream()
            ->sortBy(static function (ExampleObject $object) {
                return $object->property;
            })
            ->findFirst()
        ;
        self::assertNotNull($sortedFirstElement);
    }

    /**
     * @dataProvider sortCases
     * @test
     * @param $input
     * @param $min
     * @param $max
     */
    public function descSortingWithExtractor($input, $min, $max): void
    {
        $sortedCollection = $this->createCollection($input)
            ->stream()
            ->map(static function (int $num) {
                return TestInteger::of($num);
            })
            ->sortByDesc(static function (TestInteger $integer) {
                return $integer->getValue();
            })
            ->getCollection()
        ;

        $actualMax = $sortedCollection->stream()->findFirst();
        $actualMin = $sortedCollection->stream()->findLast();

        $this->assertEquals($min, $actualMin->getValue());
        $this->assertEquals($max, $actualMax->getValue());
    }

    /**
     * @test
     */
    public function limitedWalkCheck(): void
    {
        $invokesCounter = new InvokeCounter();
        CollectionFactory::numbers(10)
            ->stream()
            ->walk($invokesCounter, 5);

        $this->assertEquals(5, $invokesCounter->countOfInvokes());
    }

    /**
     * @test
     */
    public function suspendedWalkCheck(): void
    {
        CollectionFactory::numbers(10)
            ->stream()
            ->walk(function ($i) {
                if ($i === 2) {
                    return false;
                }
                if ($i > 2) {
                    $this->fail('El this index > 2 should not be here');
                }
                return null;
            });

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function checkLimitStream(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->limit(4)
            ->getCollection()
        ;

        $this->assertEquals(4, $collection->size());
    }

    public function reverseCases(): array
    {
        return [
            [[1, 2, 3], [3, 2, 1]],
            [['s', 2, 'sad'], ['sad', 2, 's']],
            [[1], [1]],
            [[], []]
        ];
    }

    /**
     * @dataProvider reverseCases
     * @test
     * @param $array
     * @param $reversed
     */
    public function checkReverse($array, $reversed): void
    {
        $actual = $this->createCollection($array)
            ->stream()
            ->reverse()
            ->getCollection()
            ->toArray()
        ;
        $this->assertEquals($reversed, $actual);
    }

    /**
     * @test
     */
    public function findFirstChecking(): void
    {
        $first = CollectionFactory::numbers(4, 7)
            ->stream()
            ->findFirst()
        ;

        $this->assertEquals(4, $first);
    }

    /**
     * @test
     */
    public function copyOfCollectionWhenStreaming(): void
    {
        $source = CollectionFactory::generate(2);
        $dest = $source->stream()
            ->getCollection();

        $this->assertNotSame($dest, $source);
    }

    /**
     * @test
     */
    public function gettingSetStructure(): void
    {
        $source = CollectionFactory::generate(3);
        $this->assertThat($source, $this->isInstanceOf(ListSequence::class));
        $set = $source
            ->stream()
            ->getSet()
        ;
        $this->assertThat($set, $this->isInstanceOf(Set::class));
    }
}
