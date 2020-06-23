<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Predicates;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class PredicateFunctionTest extends TestCase
{

    use CollectionAwareTrait;

    /**
     * @test
     */
    public function notResistanceChecking(): void
    {
        $f = Predicates::notResistance();

        for ($i = 0; $i < 10; $i++) {
            $this->assertTrue($f($i));
        }
    }

    /**
     * @test
     */
    public function notResistanceIntegratedChecking(): void
    {
        $actualSize = CollectionFactory::generate(10)
            ->stream()
            ->filter(Predicates::notResistance())
            ->getCollection()
            ->size();

        $this->assertEquals(10, $actualSize);
    }
}