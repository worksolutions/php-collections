<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;

class CollectionFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function sequenceGenerating(): void
    {
        $sizeOfTen = CollectionFactory::generate(10)
            ->size();
        $this->assertEquals(10, $sizeOfTen);
    }

    /**
     * @test
     */
    public function failSequenceGenerating(): void
    {
        $this->expectException(RuntimeException::class);
        CollectionFactory::generate(-10);
    }

    /**
     * @test
     */
    public function sequenceWithGeneratorGenerating(): void
    {
        $i = 0;
        $sizeOfTen = CollectionFactory::generate(10, static function () use (& $i) {
                return $i++;
            })
            ->size();
        $this->assertEquals(10, $sizeOfTen);
    }

    /**
     * @test
     */
    public function numbersFactoryTest(): void
    {
        $sizeOfTwenty = CollectionFactory::numbers(-10, 10)->size();
        $this->assertEquals(21, $sizeOfTwenty);

        $sizeOfTen = CollectionFactory::numbers(10)->size();
        $this->assertEquals(10, $sizeOfTen);

        $this->expectException(RuntimeException::class);
        CollectionFactory::numbers(-10);
    }

    /**
     * @test
     */
    public function creatingFromArray(): void
    {
        $collection = CollectionFactory::from([1, 2, 3]);

        $this->assertEquals(3, $collection->size());
        $this->assertThat($collection, CollectionIsEqual::to([1, 2, 3]));
    }
}
