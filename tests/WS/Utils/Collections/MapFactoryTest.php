<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use ArrayIterator;
use PHPUnit\Framework\TestCase;

class MapFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function emptyMapIsCreated(): void
    {
        $map = MapFactory::emptyObject();
        self::assertInstanceOf(Map::class, $map);
        self::assertEquals(0, $map->size());
    }

    /**
     * @test
     */
    public function mapFromIterableIsCreated(): void
    {
        $arrayIterator = new ArrayIterator(['a' => 'A', 'b' => 'B']);
        $map = MapFactory::fromIterable($arrayIterator);
        self::assertEquals(2, $map->size());
        self::assertEquals(['a', 'b'], $map->keys()->toArray());
        self::assertEquals(['A', 'B'], $map->values()->toArray());
    }

    /**
     * @test
     */
    public function mapFromAssocArrayCreated(): void
    {
        $assoc = ['a' => 'A', 'b' => 'B'];
        $map = MapFactory::assoc($assoc);
        self::assertEquals(2, $map->size());
        self::assertEquals(['a', 'b'], $map->keys()->toArray());
        self::assertEquals(['A', 'B'], $map->values()->toArray());
    }
}
