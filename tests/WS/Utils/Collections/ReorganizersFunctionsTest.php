<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Reorganizers;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;

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
}
