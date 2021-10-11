<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Predicates;
use WS\Utils\Collections\Functions\Reorganizers;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\TestInteger;

class ReorganizersFunctionsTest extends TestCase
{

    use CollectionConstructorTrait;

    /**
     * @test
     */
    public function chunking(): void
    {
        $collection = self::toCollection(1, 2, 3, 4, 5, 6);
        $chunkedCollection = $collection
            ->stream()
            ->reorganize(Reorganizers::chunk(2))
            ->getCollection()
        ;

        $this->assertEquals(3, $chunkedCollection->size());
        $this->assertThat($chunkedCollection->stream()->findFirst(), CollectionIsEqual::to([1, 2]));
        $this->assertThat($chunkedCollection->stream()->findLast(), CollectionIsEqual::to([5, 6]));
    }

    /**
     * @test
     */
    public function collapsing(): void
    {
        $collection = self::toCollection([1, 2], [3, 4], [5, 6]);

        $collapsedCollection = $collection
            ->stream()
            ->reorganize(Reorganizers::collapse())
            ->getCollection()
        ;
        $this->assertThat($collapsedCollection, CollectionIsEqual::to([1, 2, 3, 4, 5, 6]));
    }

    /**
     * @test
     */
    public function singleDepthCollapsing(): void
    {
        $collection = self::toCollection([1, 2], [3, 4], [5, 6, [7, 8]]);

        $collapsedCollection = $collection
            ->stream()
            ->reorganize(Reorganizers::collapse(1))
            ->getCollection()
        ;
        $this->assertThat($collapsedCollection, CollectionIsEqual::to([1, 2, 3, 4, 5, 6, [7, 8]]));
    }

    /**
     * @test
     */
    public function numericDepthCollapsing(): void
    {
        $collection = self::toCollection([1, 2], [3, 4], [5, 6, [7, 8, [9, 10]]]);

        $collapsedCollection = $collection
            ->stream()
            ->reorganize(Reorganizers::collapse(3))
            ->getCollection()
        ;
        $this->assertThat($collapsedCollection, CollectionIsEqual::to([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]));
    }

    /**
     * @test
     */
    public function filterDistinctElements(): void
    {
        $o1 = new TestInteger(1);
        $o2 = new TestInteger(2);
        $o3 = new TestInteger(3);
        $o4 = new TestInteger(1);
        $o5 = new TestInteger(2);

        $uniqCollection = CollectionFactory::from([$o1, $o2, $o3, $o4, $o5])
            ->stream()
            ->filter(Predicates::lockDuplicated())
            ->getCollection()
        ;

        self::assertThat($uniqCollection, CollectionIsEqual::to([$o1, $o2, $o3]));
    }

    /**
     * @test
     */
    public function filterDistinctCastedValues(): void
    {
        $caster = static function (int $number) {
            return $number % 2;
        };

        $result = CollectionFactory::numbers(0, 10)
            ->stream()
            ->filter(Predicates::lockDuplicated($caster))
            ->toArray()
        ;

        $this->assertCount(2, $result);
    }
}
