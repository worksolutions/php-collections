<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Consumers;

class ConsumersFunctionsTest extends TestCase
{

    use CollectionConstructorTrait;
    /**
     * @test
     */
    public function dumping(): void
    {
        ob_start();
        self::toCollection(1, 2, 3)
            ->stream()
            ->each(Consumers::dump())
        ;
        $str = ob_get_clean();
        $this->assertNotEmpty($str);
    }
}