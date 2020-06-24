<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Utils\TestInteger;

class ArrayListTest extends TestCase
{
    use CollectionInterfaceTestTrait;
    use ListInterfaceTestTrait;

    public function createInstance(...$args): ListSequence
    {
        return ArrayList::of(...$args);
    }

    /**
     * @test
     */
    public function gettingByIndex(): void
    {
        $list = $this->createInstance(1, 2, 3);

        $this->assertEquals(1, $list->get(0));
        $this->assertEquals(2, $list->get(1));
        $this->assertEquals(3, $list->get(2));
    }

    /**
     * @test
     */
    public function settingAtIndex(): void
    {
        $list = $this->createInstance(1, 2, 3);

        $list->set(4, 1);

        $this->assertEquals(1, $list->get(0));
        $this->assertEquals(4, $list->get(1));
        $this->assertEquals(3, $list->get(2));
    }

    /**
     * @test
     */
    public function gettingIndexOf(): void
    {
        $list = $this->createInstance(1, 2, 3);

        $this->assertEquals(0, $list->indexOf(1));
        $this->assertEquals(1, $list->indexOf(2));
        $this->assertEquals(2, $list->indexOf(3));
    }

    /**
     * @test
     */
    public function indexOfObjectGetting(): void
    {
        $i1 = new TestInteger(1);
        $i2 = new TestInteger(2);
        $i3 = new TestInteger(3);

        $list = $this->createInstance($i1, $i2, $i3);

        $this->assertEquals(0, $list->indexOf($i1));
        $this->assertEquals(1, $list->indexOf($i2));
        $this->assertEquals(2, $list->indexOf($i3));
    }

    /**
     * @test
     */
    public function lastIndexOfElementGetting(): void
    {
        $i0 = new TestInteger(0);
        $i1 = new TestInteger(1);
        $i2 = new TestInteger(2);
        $i3 = new TestInteger(3);

        $list = $this->createInstance($i0, $i1, $i2, $i3, $i2, $i1);

        $this->assertEquals(4, $list->lastIndexOf($i2));
        $this->assertEquals(5, $list->lastIndexOf($i1));
        $this->assertEquals(3, $list->lastIndexOf($i3));
        $this->assertEquals(0, $list->lastIndexOf($i0));
    }

    /**
     * @test
     */
    public function lastIndexGettingOfEmptyCollection(): void
    {
        $list = $this->createInstance();

        $res = $list->lastIndexOf(10);

        $this->assertNull($res);
    }

    /**
     * @test
     */
    public function removingAtPositionElement(): void
    {
        $list = $this->createInstance(1, 2, 3);

        $el = $list->removeAt(0);

        $this->assertEquals(1, $el);
        $this->assertEquals(2, $list->get(0));
        $this->assertEquals(2, $list->size());
    }

    /**
     * @test
     */
    public function removingAtEmptyCollection(): void
    {
        $list = $this->createInstance();

        $res = $list->removeAt(10);

        $this->assertNull($res);
    }

    /**
     * @test
     */
    public function removingWithoutGaps(): void
    {
        $list = $this->createInstance(1, 2, 3, 4);

        $list->remove(2);

        $this->assertEquals(3, $list->size());
        $this->assertEquals(3, $list->get(1));
    }
}
