<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Predicates;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\ExampleObject;

class PredicatesFunctionsTest extends TestCase
{

    use CollectionConstructorTrait;

    /**
     * @test
     */
    public function notNullFilter(): void
    {
        $collection = self::toCollection(null, 1, 2, 3, null);
        $this->assertEquals(5, $collection->size());

        $notNullSize = $collection
            ->stream()
            ->filter(Predicates::notNull())
            ->getCollection()
            ->size()
        ;
        $this->assertEquals(3, $notNullSize);
    }

    /**
     * @test
     */
    public function notResistanceFilter(): void
    {
        $collection = self::toCollection(null, 1, 2, 3, null);

        $after = $collection
            ->stream()
            ->filter(Predicates::notResistance())
            ->getCollection()
        ;

        $this->assertThat($after, CollectionIsEqual::to($collection));
    }

    /**
     * @test
     */
    public function lockFilter(): void
    {
        $collection = self::toCollection(null, 1, 2, 3, null)
            ->stream()
            ->filter(Predicates::lock())
            ->getCollection()
        ;

        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     */
    public function matchingPropertyFiltering(): void
    {
        $o1 = (new ExampleObject())->setName('first');
        $o2 = (new ExampleObject())->setName('first');
        $o3 = (new ExampleObject())->setName('second');
        $o4 = (new ExampleObject())->setName('third');

        $collection = self::toCollection($o1, $o2, $o3, $o4);

        $filtered = $collection
            ->stream()
            ->filter(Predicates::where('name', 'first'))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([$o1, $o2]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::whereNot('name', 'third'))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([$o1, $o2, $o3]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::whereIn('name', ['second', 'third']))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([$o3, $o4]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::whereNotIn('name', ['second', 'third']))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([$o1, $o2]));
    }

    /**
     * @test
     */
    public function matchingValuesFiltering(): void
    {
        $collection = self::toCollection(1, 2, 3, 4, 5);

        $filtered = $collection
            ->stream()
            ->filter(Predicates::equal(2))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([2]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::not(2))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([1, 3, 4, 5]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::moreThan(3))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([4, 5]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::moreOrEqual(3))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([3, 4, 5]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::lessThan(3))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([1, 2]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::lessOrEqual(3))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([1, 2, 3]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::in([3, 4, 5, 6]))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([3, 4, 5]));

        $filtered = $collection
            ->stream()
            ->filter(Predicates::notIn([3, 4, 5, 6]))
            ->getCollection()
        ;
        $this->assertThat($filtered, CollectionIsEqual::to([1, 2]));
    }
}
