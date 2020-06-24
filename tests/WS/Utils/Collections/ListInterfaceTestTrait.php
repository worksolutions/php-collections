<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use Traversable;

trait ListInterfaceTestTrait
{
    /**
     * @test
     */
    public function iterating(): void
    {
        $collection = $this->createInstance(27, 'string', -11, 50);
        $iterator = $collection->getIterator();

        $this->assertInstanceOf(Traversable::class, $iterator);
        $this->assertEquals(27, $iterator->current());
        $iterator->next();
        $this->assertEquals('string', $iterator->current());
        $this->assertEquals(1, $iterator->key());
    }
}
