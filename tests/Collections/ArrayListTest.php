<?php
/**
 * @author Maxim Sokolovsky
 */

namespace Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\ArrayList;
use WS\Utils\Collections\Functions\Aggregators;

class ArrayListTest extends TestCase
{

    /**
     * @test
     */
    public function hello(): void
    {
        $this->assertTrue(true);
    }

    public function testAdd(): void
    {
        $list = ArrayList::of(1, 2);
        $this->assertEquals(2, $list->size());

        $this->assertTrue($list->add(-76));
        $this->assertEquals(3, $list->size());

        $anotherList = new ArrayList();
        $this->assertTrue($anotherList->add('string'));
        $anotherList->merge($list);
        $this->assertEquals(4, $anotherList->size());
    }

    public function testMerge(): void
    {
        $list = ArrayList::of(1, 2);
        $anotherList = ArrayList::of(3, 4, 5);
        $clonedList = clone $list;
        $list->merge($anotherList);
        $this->assertEquals([1, 2, 3, 4, 5], $list->toArray());
        $anotherList->merge($clonedList);
        $this->assertEquals([3, 4, 5, 1, 2], $anotherList->toArray());
    }

    public function testClear(): void
    {
        $list = ArrayList::of(27, 'string');
        $list->clear();
        $this->assertEquals(0, $list->size());
    }

    public function testRemove(): void
    {
        $list = ArrayList::of(27, 'string', -11, 50);

        $this->assertTrue($list->remove(-11));
        $this->assertEquals(3, $list->size());
        $this->assertEquals([27, 'string', 3 => 50], $list->toArray());

        $this->assertTrue($list->remove('string'));
        $this->assertEquals(2, $list->size());
        $this->assertEquals([27, 3 => 50], $list->toArray());

        $this->assertFalse($list->remove(89));
        $this->assertEquals(2, $list->size());
        $this->assertEquals([27, 3 => 50], $list->toArray());
    }

    public function testContains(): void
    {
        $list = ArrayList::of(27, 'string', -11, 50);
        $this->assertTrue($list->contains('string'));
        $this->assertTrue($list->contains(-11));
        $this->assertFalse($list->contains(11));
    }

    public function testEquals(): void
    {
        $list = ArrayList::of(189, 11, 789);
        $anotherList = ArrayList::of(189, 11, 789);
        $this->assertTrue($list->equals($anotherList));
        $this->assertTrue($anotherList->equals($list));
    }

    public function testSize(): void
    {
        $list = ArrayList::of(27, 'string', -11, 50);
        $this->assertEquals(4, $list->size());
        $list->remove(-11);
        $this->assertEquals(3, $list->size());
        $list->add('anotherString');
        $this->assertEquals(4, $list->size());
    }

    public function testIsEmpty(): void
    {
        $list = ArrayList::of(27, 'string', -11, 50);
        $this->assertFalse($list->isEmpty());
        $list->clear();
        $this->assertTrue($list->isEmpty());
    }

    public function testToArray(): void
    {
        $list = ArrayList::of(27, 'string', -11, 50);
        $this->assertEquals([27, 'string', -11, 50], $list->toArray());
    }

    public function testIterator(): void
    {
        $list = ArrayList::of(27, 'string', -11, 50);
        $iterator = $list->getIterator();

        $this->assertInstanceOf(\Traversable::class, $iterator);
        $this->assertEquals(27, $iterator->current());
        $iterator->next();
        $this->assertEquals('string', $iterator->current());
        $this->assertEquals(1, $iterator->key());
    }

    public function usingComparator(): void
    {
        $collection->filter()
            ->map()
            ->aggregate(Aggregators::strImplode(', '));
    }
}
