<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Aggregators;

class AggregatorsFunctionsTest extends TestCase
{

    use CollectionConstructorTrait;
    /**
     * @test
     */
    public function stringImploding(): void
    {
        $f = Aggregators::concat(', ');
        $this->assertEquals('1, 2, 3', $f(self::toCollection(1, 2, 3)));
    }

    /**
     * @test
     */
    public function stringImplodingIntegration(): void
    {
        $res = self::toCollection(1, 2, 3)
            ->stream()
            ->aggregate(Aggregators::concat(', '));

        $this->assertEquals('1, 2, 3', $res);
    }

    /**
     * @test
     */
    public function averageCalculating(): void
    {
        $f = Aggregators::average();
        $this->assertEquals(2, $f(self::toCollection(1, 2, 3)));
    }

    /**
     * @test
     */
    public function averageCalculatingIntegration(): void
    {
        $res = self::toCollection(1, 2, 3)
            ->stream()
            ->aggregate(Aggregators::average());

        $this->assertEquals(2, $res);
    }
}
