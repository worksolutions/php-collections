<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Iterator;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class CallbackIteratorTest extends TestCase
{
    /**
     * @test
     */
    public function iterate(): void
    {
        $i = new CallbackIterator(new IntGeneratorCallback(2));
        $i->next();
        $one = $i->next();
        $this->assertEquals(1, $one);
    }

    /**
     * @test
     */
    public function hasNextChecking(): void
    {
        $i = new CallbackIterator(new IntGeneratorCallback(2));
        $i->next();
        $i->next();

        $this->assertFalse($i->hasNext());
    }

    /**
     * @test
     */
    public function getOverflowException(): void
    {
        $i = new CallbackIterator(new IntGeneratorCallback(2));
        $i->next();
        $i->next();

        $this->expectException(RuntimeException::class);

        $i->next();
    }
}
