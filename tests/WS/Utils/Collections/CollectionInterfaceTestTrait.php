<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use Traversable;

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
        $this->assertEquals([1, 2, 3, 4, 5], $collection->toArray());
        $anotherCollection->merge($clonedCollection);
        $this->assertEquals([3, 4, 5, 1, 2], $anotherCollection->toArray());
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
        $this->assertEquals([27, 'string', 50], $collection->toArray());

        $this->assertTrue($collection->remove('string'));
        $this->assertEquals(2, $collection->size());
        $this->assertEquals([27, 50], $collection->toArray());

        $this->assertFalse($collection->remove(89));
        $this->assertEquals(2, $collection->size());
        $this->assertEquals([27, 50], $collection->toArray());
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
        $this->assertEquals([27, 'string', -11, 50], $collection->toArray());
    }

    /**
     * @test
     */
    public function iterating(): void
    {
        $collection = $this->createInstance(27, 'string', -11, 50);
        $iterator = $collection->getIterator();

        $this->assertInstanceOf(Traversable::class, $iterator);
        $this->assertEquals(27, $iterator->current());
        $iterator->next();
        $this->assertEquals('string', $iterator->current());
        $this->assertEquals(1, $iterator->key());
    }
}