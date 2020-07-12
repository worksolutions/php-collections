<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Collectors;

class AggregatorsFunctionsTest extends TestCase
{

    use CollectionConstructorTrait;
    /**
     * @test
     */
    public function stringImploding(): void
    {
        $f = Collectors::concat(', ');
        $this->assertEquals('1, 2, 3', $f(self::toCollection(1, 2, 3)));
    }

    /**
     * @test
     */
    public function stringImplodingIntegration(): void
    {
        $res = self::toCollection(1, 2, 3)
            ->stream()
            ->collect(Collectors::concat(', '));

        $this->assertEquals('1, 2, 3', $res);
    }

    /**
     * @test
     */
    public function averageCalculating(): void
    {
        $f = Collectors::average();
        $this->assertEquals(2, $f(self::toCollection(1, 2, 3)));
    }

    /**
     * @test
     */
    public function averageCalculatingIntegration(): void
    {
        $res = self::toCollection(1, 2, 3)
            ->stream()
            ->collect(Collectors::average());

        $this->assertEquals(2, $res);
    }
}
