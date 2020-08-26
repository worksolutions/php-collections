<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;

class ImmutableStreamTest extends TestCase
{

    use CollectionConstructorTrait;

    /**
     * @test
     */
    public function shouldBeImmutableInReorganizeWithCollection(): void
    {
        $stream = self::toCollection(1, 2, 3)
            ->stream();

        $collection = $stream->getCollection();

        $stream
            ->reorganize(function (Collection $c) {
                $c->clear();
                return $this->toCollection();
            });

        self::assertThat($collection, CollectionIsEqual::to([1, 2, 3]));
    }

    /**
     * @test
     */
    public function shouldBeImmutableInCollectWithCollection(): void
    {
        $stream = self::toCollection(1, 2, 3)
            ->stream();

        $collection = $stream->getCollection();
        $stream
            ->collect(static function (Collection $c) {
                $c->clear();
                return 1;
            });

        self::assertThat($collection, CollectionIsEqual::to([1, 2, 3]));
    }
}
