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

    public function addingElement(): void
    {
        $list = ArrayList::of(1);
        $this->assertThat($list->size(), );
    }

    public function usingComparator(): void
    {
        $collection->filter()
            ->map()
            ->aggregate(Aggregators::strImplode(', '));
    }
}
