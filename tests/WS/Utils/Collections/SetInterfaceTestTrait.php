<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use SplObjectStorage;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\TestInteger;

trait SetInterfaceTestTrait
{
    abstract protected function createInstance(): Set;

    /**
     * @test
     */
    public function uniquenessElements(): void
    {
        $instance = $this->createInstance();

        $instance->add(1);
        $instance->add(1);
        $instance->add(1);

        self::assertEquals(1, $instance->size());
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

        self::assertEquals(1, $instance->size());
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

        self::assertEquals(1, $instance->size());
    }

    /**
     * @test
     */
    public function equalsSetChecking(): void
    {
        $instance = $this->createInstance();
        $instance->add(1);
        $instance->add(2);
        $instance->add(3);

        $anotherInstance = $this->createInstance();
        $instance->add(3);
        $instance->add(2);
        $instance->add(1);

        self::assertThat($anotherInstance, CollectionIsEqual::to($instance));
    }
}
