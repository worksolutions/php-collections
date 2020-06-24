<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Predicates;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;

class ConditionsStreamTest extends TestCase
{

    /**
     * @test
     */
    public function gettingDummyStream(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(false)
            ->filter(Predicates::lessThan(4))
            ->getCollection()
        ;
        $this->assertEquals(10, $collection->size());
    }

    /**
     * @test
     */
    public function obtainNormalStreamFromDummy(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(false)
            ->filter(Predicates::lessOrEqual(5))
            ->when(true)
            ->filter(Predicates::moreOrEqual(5))
            ->getCollection()
        ;

        $this->assertThat($collection, CollectionIsEqual::to([5, 6, 7, 8, 9]));
    }

    /**
     * @test
     */
    public function usingWithoutDummyDecorator(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(true)
            ->filter(Predicates::lessOrEqual(6))
            ->when(true)
            ->filter(Predicates::moreOrEqual(4))
            ->getCollection()
        ;

        $this->assertThat($collection, CollectionIsEqual::to([4, 5, 6]));
    }
}
