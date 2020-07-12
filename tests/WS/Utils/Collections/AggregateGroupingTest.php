<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use SplObjectStorage;
use WS\Utils\Collections\Functions\Collectors;
use WS\Utils\Collections\Utils\ExampleObject;
use WS\Utils\Collections\Utils\TestInteger;

class AggregateGroupingTest extends TestCase
{

    use CollectionConstructorTrait;
    /**
     * @test
     */
    public function groupingFromScalars(): void
    {
        /** @var Map $map */
        $map = self::toCollection(1, 2, 3, 3, 2, 2)
            ->stream()
            ->collect(Collectors::group())
        ;

        $this->assertEquals(1, $map->get(1));
        $this->assertEquals(3, $map->get(2));
        $this->assertEquals(2, $map->get(3));
    }

    /**
     * @test
     */
    public function groupingByObjects(): void
    {
        $o1 = new SplObjectStorage();
        $o2 = new SplObjectStorage();
        $o3 = new SplObjectStorage();

        $map = self::toCollection($o1, $o2, $o3, $o3, $o2, $o2)
            ->stream()
            ->collect(Collectors::group())
        ;

        $this->assertEquals(1, $map->get($o1));
        $this->assertEquals(3, $map->get($o2));
        $this->assertEquals(2, $map->get($o3));
    }

    /**
     * @test
     */
    public function groupingByObjectsWithHashCode(): void
    {
        $map = self::toCollection(
            new TestInteger(1),
            new TestInteger(2),
            new TestInteger(3),
            new TestInteger(3),
            new TestInteger(2),
            new TestInteger(2)
        )
            ->stream()
            ->collect(Collectors::group())
        ;

        $this->assertEquals(1, $map->get(new TestInteger(1)));
        $this->assertEquals(3, $map->get(new TestInteger(2)));
        $this->assertEquals(2, $map->get(new TestInteger(3)));
    }

    /**
     * @test
     */
    public function groupingByFunction(): void
    {
        $map = self::toCollection(
                1, 2, 3, 3, 2, 2
            )
            ->stream()
            ->collect(Collectors::groupBy(static function ($v) {
                return $v * 10;
            }))
        ;

        $this->assertEquals(1, $map->get(10));
        $this->assertEquals(3, $map->get(20));
        $this->assertEquals(2, $map->get(30));
    }

    /**
     * @test
     */
    public function groupingByObjectProperty(): void
    {
        $o1 = (new ExampleObject())->setName('first');
        $o2 = (new ExampleObject())->setName('second');
        $o3 = (new ExampleObject())->setName('third');
        $o31 = (new ExampleObject())->setName('third');
        $o21 = (new ExampleObject())->setName('second');
        $o22 = (new ExampleObject())->setName('second');

        $map = self::toCollection($o1, $o2, $o3, $o31, $o21, $o22)
            ->stream()
            ->collect(Collectors::groupByProperty('name'))
            ;

        $this->assertEquals(1, $map->get('first'));
        $this->assertEquals(3, $map->get('second'));
        $this->assertEquals(2, $map->get('third'));
    }
}