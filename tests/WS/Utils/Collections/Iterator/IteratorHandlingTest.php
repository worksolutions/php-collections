<?php

namespace WS\Utils\Collections\Iterator;

use ArrayIterator;
use DirectoryIterator;
use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Exception\UnsupportedException;
use WS\Utils\Collections\Functions\Predicates;
use WS\Utils\Collections\IteratorCollection;
use WS\Utils\Collections\IteratorStream;

class IteratorHandlingTest extends TestCase
{
    /**
     * @test
     */
    public function checkArrayIterator()
    {
        $arrayIterator = new ArrayIterator([0, 1, 2, 3]);
        $intGenerator = new IntGeneratorCallback(4);
        CollectionFactory::fromIterable($arrayIterator)
            ->stream()
            ->each(static function ($i) use ($intGenerator) {
                self::assertEquals($intGenerator()->getValue(), $i);
            })
        ;
    }

    /**
     * @test
     */
    public function checkPhpDirectoryIterator()
    {
        $iterable = new DirectoryIterator(__DIR__);
        $files = CollectionFactory::fromIterable($iterable)
            ->stream()
            ->map(static function (DirectoryIterator $current) {
                return $current->getBasename();
            })
            ->filter(Predicates::lockDuplicated())
            ->toArray()
        ;
        self::assertTrue(count($files) > 3);
    }

    /**
     * @test
     */
    public function checkCustomIterator()
    {
        $iterable = new StatePatternIterator(5);
        $differenceCount = CollectionFactory::fromIterable($iterable)
            ->stream()
            ->map(static function (ValueKeeper $valueKeeper) {
                return $valueKeeper->getValue();
            })
            ->filter(Predicates::lockDuplicated())
            ->getCollection()
            ->size()
        ;
        self::assertEquals(5, $differenceCount);
    }

    /**
     * @test
     */
    public function checkStateIteratorFilter()
    {
        $iterable = new StatePatternIterator(6);
        $result = CollectionFactory::fromIterable($iterable)
            ->stream()
            ->filter(static function (ValueKeeper $valueKeeper) {
                return $valueKeeper->getValue() <= 3;
            })
            ->map(static function (ValueKeeper $valueKeeper) {
                return $valueKeeper->getValue();
            })
            ->toArray()
        ;
        self::assertEquals([0, 1, 2, 3], $result);
    }

    /**
     * @test
     */
    public function checkSizeCutting()
    {
        $iterable = new StatePatternIterator(6);
        $result = CollectionFactory::fromIterable($iterable)
            ->stream()
            ->filter(static function (ValueKeeper $valueKeeper) {
                return $valueKeeper->getValue() > 0;
            })
            ->limit(2)
            ->map(static function (ValueKeeper $valueKeeper) {
                return $valueKeeper->getValue();
            })
            ->toArray()
        ;
        self::assertEquals([1, 2], $result);
    }

    /**
     * @test
     */
    public function checkRightSizeInStateIterator()
    {
        $iterable = new StatePatternIterator(3);
        $size = CollectionFactory::fromIterable($iterable)->size();
        self::assertEquals(3, $size);
    }

    /**
     * @test
     */
    public function checkEmptyIterator()
    {
        $iterable = new StatePatternIterator(0);
        self::assertTrue(CollectionFactory::fromIterable($iterable)->isEmpty());
    }

    /**
     * @test
     */
    public function checkEachBehavior()
    {
        $iterable = new StatePatternIterator(6);
        $i = 1;
        CollectionFactory::fromIterable($iterable)
            ->stream()
            ->filter(static function (ValueKeeper $valueKeeper) {
                return $valueKeeper->getValue() > 0;
            })
            ->limit(4)
            ->each(static function (ValueKeeper $valueKeeper) use (& $i) {
                self::assertEquals($i++, $valueKeeper->getValue());
            })
        ;
    }

    /**
     * @test
     */
    public function checkWalkingByStateIterator()
    {
        $iterable = new StatePatternIterator(6);
        $i = 2;
        $result = CollectionFactory::fromIterable($iterable)
            ->stream()
            ->filter(static function (ValueKeeper $keeper) {
                return $keeper->getValue() > 1;
            })
            ->walk(static function (ValueKeeper $keeper) use (& $i) {
                self::assertTrue($keeper->getValue() === $i);
                $i++;
            }, 2)
            ->map(static function (ValueKeeper $keeper) {
                return $keeper->getValue();
            })
            ->toArray()
        ;
        self::assertEquals([2, 3, 4, 5], $result);
    }

    /**
     * @test
     */
    public function checkWalkingWithStopping()
    {
        $iterable = new StatePatternIterator(6);
        $i = 2;
        $result = CollectionFactory::fromIterable($iterable)
            ->stream()
            ->walk(static function () use (& $i) {
                if ($i <= 0) {
                    return false;
                }
                $i--;
                return true;
            })
            ->map(static function (ValueKeeper $keeper) {
                return $keeper->getValue();
            })
            ->getCollection()
        ;
        self::assertEquals(6, $result->size());
        self::assertEquals(0, $i);
    }

    /**
     * @test
     */
    public function withTwoElementsChecking()
    {
        $iterable = new StatePatternIterator(2);
        $collection = CollectionFactory::fromIterable($iterable);

        self::assertInstanceOf(IteratorCollection::class, $collection);
        self::assertInstanceOf(IteratorStream::class, $collection->stream());
    }

    /**
     * @test
     */
    public function checkReduceMethod()
    {
        $iterator = new StatePatternIterator(5);
        $sumOfThree = CollectionFactory::fromIterable($iterator)
            ->stream()
            ->filter(static function (ValueKeeper $keeper) {
                return $keeper->getValue() > 1;
            })
            ->reduce(static function (ValueKeeper $keeper, $sum) {
                return $sum + $keeper->getValue();
            }, 0)
        ;
        self::assertEquals(9, $sumOfThree);
    }

    /**
     * @test
     */
    public function allMatchIteratorChecking()
    {
        $iterator = new StatePatternIterator(5);
        $collection = CollectionFactory::fromIterable($iterator);

        $everythingIsInt = $collection
            ->stream()
            ->allMatch(static function (ValueKeeper $keeper) {
                return is_int($keeper->getValue());
            })
        ;

        $greatThanTwoPredicate = static function (ValueKeeper $keeper) {
            return $keeper->getValue() > 2;
        };
        $everythingIsGreatThanTwo = $collection
            ->stream()
            ->allMatch($greatThanTwoPredicate)
        ;

        $everythingIsGreatThanTwoWithFilter = $collection
            ->stream()
            ->filter($greatThanTwoPredicate)
            ->allMatch($greatThanTwoPredicate)
        ;

        self::assertTrue($everythingIsInt);
        self::assertFalse($everythingIsGreatThanTwo);
        self::assertTrue($everythingIsGreatThanTwoWithFilter);
    }

    /**
     * @test
     */
    public function anyMatchIteratorChecking()
    {
        $greatThanTwoPredicate = static function (ValueKeeper $keeper) {
            return $keeper->getValue() > 2;
        };
        $greatThanTenPredicate = static function (ValueKeeper $keeper) {
            return $keeper->getValue() > 10;
        };

        $iterator = new StatePatternIterator(5);
        $collection = CollectionFactory::fromIterable($iterator);

        $hasElementsWithGreatThanWho = $collection
            ->stream()
            ->anyMatch($greatThanTwoPredicate)
        ;

        $hasElementsWithGreatThanTen = $collection
            ->stream()
            ->anyMatch($greatThanTenPredicate)
        ;

        $hasLessThanTwoFiltered = $collection
            ->stream()
            ->filter($greatThanTwoPredicate)
            ->anyMatch(static function (ValueKeeper $keeper) {
                return $keeper->getValue() <= 2;
            })
        ;

        self::assertTrue($hasElementsWithGreatThanWho);
        self::assertFalse($hasElementsWithGreatThanTen);
        self::assertFalse($hasLessThanTwoFiltered);
    }

    /**
     * @test
     */
    public function cunningMapChecking()
    {
        $iterator = new StatePatternIterator(3);
        self::expectException(UnsupportedException::class);
        CollectionFactory::fromIterable($iterator)
            ->stream()
            ->map(static function ($item) {
                return $item;
            })
            ->getCollection()
        ;
    }

    /**
     * @test
     */
    public function iterableStreamFlowChecking()
    {
        $greatThanTwoPredicate = static function (ValueKeeper $keeper) {
            return $keeper->getValue() > 2;
        };

        $iterator = new StatePatternIterator(5);

        $array = CollectionFactory::fromIterable($iterator)
            ->stream()
            ->when(false)
            ->filter($greatThanTwoPredicate)
            ->always()
            ->map(static function (ValueKeeper $keeper) {
                return $keeper->getValue();
            })
            ->toArray()
        ;
        self::assertCount(5, $array);

        $stream = CollectionFactory::fromIterable($iterator)
            ->stream()
            ->when(true)
            ->always()
        ;
        self::assertInstanceOf(IteratorStream::class, $stream);
    }
}

