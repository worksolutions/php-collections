<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use SplObjectStorage;
use WS\Utils\Collections\Utils\TestInteger;

trait SetInterfaceTestTrait
{
    /**
     * @test
     */
    public function uniquenessElements(): void
    {
        $instance = $this->createInstance(1);

        $instance->add(1);
        $instance->add(1);
        $instance->add(1);

        $this->assertEquals(1, $instance->size());
    }

    /**
     * @test
     */
    public function uniquenessObjectElements(): void
    {
        $instance = $this->createInstance();

        $ob = new SplObjectStorage();
        $instance->add($ob);
        $instance->add($ob);
        $instance->add($ob);

        $this->assertEquals(1, $instance->size());
    }

    /**
     * @test
     */
    public function uniquenessHashCodeAwareObjects(): void
    {
        $instance = $this->createInstance();

        $instance->add(new TestInteger(1));
        $instance->add(new TestInteger(1));
        $instance->add(new TestInteger(1));

        $this->assertEquals(1, $instance->size());
    }
}
