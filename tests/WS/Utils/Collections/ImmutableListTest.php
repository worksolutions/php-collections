<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Traversable;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;

class ImmutableListTest extends TestCase
{

    /**
     * Returns instance for testing
     * @param mixed ...$elements
     * @return ImmutableList
     */
    public function createInstance(...$elements): ImmutableList
    {
        return new ImmutableList($elements);
    }

    public function deniedModifyingCalls(): array
    {
        $collection = CollectionFactory::numbers(5);
        return [
            ['add', [1]],
            ['addAll', [$collection]],
            ['merge', [$collection]],
            ['remove', [1]],
            ['set', [1, 1]],
            ['removeAt', [0]],
            ['clear', []],
        ];
    }

    /**
     * @dataProvider deniedModifyingCalls
     * @test
     */
    public function tryToModifying($method, $args): void
    {
        $list = $this->createInstance();

        $this->expectException(RuntimeException::class);
        call_user_func_array([$list, $method], $args);
    }

    /**
     * @test
     */
    public function gettingListInfo(): void
    {
        $list = $this->createInstance(1, 2, 3);

        $this->assertEquals(2, $list->get(1));
        $this->assertTrue($list->contains(3));
        $this->assertEquals(3, $list->size());
        $this->assertEquals(2, $list->indexOf(3));
        $this->assertEquals(0, $list->lastIndexOf(1));
        $this->assertFalse($list->isEmpty());
        $this->assertTrue($list->equals(ArrayList::of(1, 2, 3)));
        $this->assertEquals([1, 2, 3], $list->toArray());
        $this->assertThat($list->copy(), CollectionIsEqual::to($list));
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(Stream::class, $list->stream());
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertInstanceOf(Traversable::class, $list->getIterator());
    }

    /**
     * @test
     */
    public function creating(): void
    {
        $instance = ImmutableList::fromCollection($this->createInstance(1, 2));
        $this->assertEquals(2, $instance->size());

        $instance = ImmutableList::of(1, 2);
        $this->assertEquals(2, $instance->size());
    }
}
