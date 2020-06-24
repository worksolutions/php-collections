<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use WS\Utils\Collections\UnitConstraints\CollectionContainsSameElements;
use WS\Utils\Collections\Utils\TestInteger;

trait CollectionInterfaceTestTrait
{
    /**
     * @test
     */
    public function adding(): void
    {
        /** @var Collection $instance */
        $instance = $this->createInstance(1, 2);
        $this->assertEquals(2, $instance->size());

        $this->assertTrue($instance->add(-76));
        $this->assertEquals(3, $instance->size());

        $anotherInstance = $this->createInstance();
        $this->assertTrue($anotherInstance->add('string'));
        $anotherInstance->merge($instance);
        $this->assertEquals(4, $anotherInstance->size());
    }

    /**
     * @test
     */
    public function merging(): void
    {
        /** @var Collection $collection */
        $collection = $this->createInstance(1, 2);

        /** @var Collection $anotherCollection */
        $anotherCollection = $this->createInstance(3, 4, 5);

        $clonedCollection = clone $collection;
        $collection->merge($anotherCollection);
        $this->assertThat($collection, CollectionContainsSameElements::with([1, 2, 3, 4, 5]));
        $anotherCollection->merge($clonedCollection);
        $this->assertThat($anotherCollection, CollectionContainsSameElements::with([3, 4, 5, 1, 2]));
    }

    /**
     * @test
     */
    public function clearing(): void
    {
        /** @var Collection $collection */
        $collection = $this->createInstance(27, 'string');
        $collection->clear();
        $this->assertEquals(0, $collection->size());
    }

    /**
     * @test
     */
    public function removing(): void
    {
        /** @var Collection $collection */
        $collection = $this->createInstance(27, 'string', -11, 50);

        $this->assertTrue($collection->remove(-11));
        $this->assertEquals(3, $collection->size());
        $this->assertThat($collection, CollectionContainsSameElements::with([27, 'string', 50]));

        $this->assertTrue($collection->remove('string'));
        $this->assertEquals(2, $collection->size());
        $this->assertThat($collection, CollectionContainsSameElements::with([27, 50]));

        $this->assertFalse($collection->remove(89));
        $this->assertEquals(2, $collection->size());
        $this->assertThat($collection, CollectionContainsSameElements::with([27, 50]));
    }

    /**
     * @test
     */
    public function removingAbsent(): void
    {
        /** @var Collection $collection */
        $collection = $this->createInstance(1, 2, 3);
        $removingRes = $collection->remove(4);

        $this->assertFalse($removingRes);
        $this->assertEquals(3, $collection->size());
    }

    /**
     * @test
     */
    public function removingFromEmptyCollection(): void
    {
        $collection = $this->createInstance();

        $removingRes = $collection->remove(4);

        $this->assertFalse($removingRes);
        $this->assertEquals(0, $collection->size());
    }

    /**
     * @test
     */
    public function containingCheck(): void
    {
        $collction = $this->createInstance(27, 'string', -11, 50);
        $this->assertTrue($collction->contains('string'));
        $this->assertTrue($collction->contains(-11));
        $this->assertFalse($collction->contains(11));
    }

    /**
     * @test
     */
    public function equivalence(): void
    {
        $collection = $this->createInstance(189, 11, 789);
        $anotherCollection = $this->createInstance(189, 11, 789);
        $this->assertTrue($collection->equals($anotherCollection));
        $this->assertTrue($anotherCollection->equals($collection));
    }

    /**
     * @test
     */
    public function sizeDetection(): void
    {
        $collection = $this->createInstance(27, 'string', -11, 50);
        $this->assertEquals(4, $collection->size());
        $collection->remove(-11);
        $this->assertEquals(3, $collection->size());
        $collection->add('anotherString');
        $this->assertEquals(4, $collection->size());
    }

    /**
     * @test
     */
    public function emptiness(): void
    {
        $collection = $this->createInstance(27, 'string', -11, 50);
        $this->assertFalse($collection->isEmpty());
        $collection->clear();
        $this->assertTrue($collection->isEmpty());
    }

    /**
     * @test
     */
    public function arrayGenerating(): void
    {
        $collection = $this->createInstance(27, 'string', -11, 50);
        $this->assertThat($collection, CollectionContainsSameElements::with([27, 'string', -11, 50]));
    }

    /**
     * @test
     */
    public function coping(): void
    {
        /** @var Collection $i1 */
        $i1 = $this->createInstance(3, 2, 1);
        $i2 = $i1->copy();

        $this->assertEquals($i1->toArray(), $i2->toArray());
        $this->assertNotSame($i1, $i2);
    }

    /**
     * @test
     */
    public function streaming(): void
    {
        $i = $this->createInstance();

        $this->assertInstanceOf(Stream::class, $i->stream());
    }

    /**
     * @test
     */
    public function addingGroupOffElements(): void
    {
        /** @var Collection $i */
        $i = $this->createInstance(1, 2, 3);

        $i->addAll([4, 5, 6]);

        $this->assertEquals(6, $i->size());
        $this->assertThat($i, CollectionContainsSameElements::with([1, 2, 3, 4, 5, 6]));
    }

    /**
     * @test
     */
    public function removingWithCollectionAwareInterface(): void
    {
        $i1 = new TestInteger(1);
        $i2 = new TestInteger(2);
        $i3 = new TestInteger(3);

        /** @var Collection $collection */
        $collection = $this->createInstance($i1, $i2, $i3);
        $res = $collection->remove(new TestInteger(2));

        $this->assertTrue($res);
        $this->assertEquals(2, $collection->size());
        $this->assertFalse($collection->contains($i2));
    }

    /**
     * @test
     */
    public function removingByAwareCollectionInterfaceWithMixedCollection(): void
    {
        $i1 = new TestInteger(1);
        $i2 = new TestInteger(2);
        $i3 = new TestInteger(3);
        $i4 = 4;

        /** @var Collection $collection */
        $collection = $this->createInstance($i1, $i2, $i3,$i4);

        $res = $collection->remove(new TestInteger(2));

        $this->assertTrue($res);
        $this->assertEquals(3, $collection->size());
        $this->assertFalse($collection->contains($i2));

        $this->assertTrue($collection->contains(4));

        $res = $collection->remove(4);

        $this->assertTrue($res);
        $this->assertEquals(2, $collection->size());
        $this->assertFalse($collection->contains(4));

        $res = $collection->remove(new TestInteger(2));

        $this->assertFalse($res);
        $this->assertEquals(2, $collection->size());
        $this->assertFalse($collection->contains($i2));
    }
}
