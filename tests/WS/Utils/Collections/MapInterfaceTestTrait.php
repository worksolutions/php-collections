<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use Exception;
use SplObjectStorage;
use WS\Utils\Collections\Utils\TestInteger;

/**
 * Trait MapInterfaceTest
 * @package WS\Utils\Collections
 */
trait MapInterfaceTestTrait
{
    /**
     * @test
     */
    public function checkSameObjectAsKey(): void
    {
        $obj1 = $obj2 = new SplObjectStorage();
        /** @var $instance Map */
        $instance = $this->createInstance();
        $instance->put($obj1, null);
        $this->assertTrue($instance->containsKey($obj2));

        $this->assertNull($instance->get($obj2));
    }

    /**
     * @test
     */
    public function checkCount(): void
    {
        $instance = $this->createInstance();
        $instance->put(1,1);
        $instance->put(2,1);
        $instance->put(2,1);
        $instance->put(3,1);
        $instance->put(4,1);
        $instance->put(5,1);

        $instance->remove(4);

        $this->assertEquals(4, $instance->size());
    }

    /**
     * @test
     */
    public function iterate(): void
    {
        $instance = $this->createInstance();
        for ($i = 10; $i < 20; $i++) {
            $instance->put($i, $i+1);
        }

        $i = 10;
        foreach ($instance as $k => $v) {
            $this->assertSame($k, $v - 1);
            $this->assertSame($i, $k);
            $i++;
        }
    }

    /**
     * @test
     */
    public function keySetGetting(): void
    {
        /** @var Map $instance */
        $instance = $this->createInstance();
        $instance->put(1,1);
        $instance->put(2,1);
        $instance->put(2,1);
        $instance->put(3,1);
        $instance->put(4,1);
        $instance->put(null,1);

        $instance->remove(4);

        $set = $instance->keys();

        $this->assertEquals(4, $set->size());
    }

    /**
     * @test
     */
    public function valuesGetting(): void
    {
        /** @var Map $instance */
        $instance = $this->createInstance();
        $instance->put(1,1);
        $instance->put(2,1);
        $instance->put(2,1);
        $instance->put(3,1);
        $instance->put(4,1);
        $instance->put(5,1);

        $instance->remove(4);

        $values = $instance->values();

        $this->assertEquals(4, $values->size());
    }

    /**
     * @test
     */
    public function hashCodeAwareChecking(): void
    {
        $o1 = new TestInteger(1);
        $o2 = new TestInteger(2);
        $o3 = new TestInteger(3);

        $instance = $this->createInstance();
        $instance->put($o1, null);
        $instance->put($o2, null);
        $instance->put($o3, null);

        $this->assertTrue($instance->containsKey(new TestInteger(1)));
        $this->assertTrue($instance->containsKey(new TestInteger(2)));
        $this->assertTrue($instance->containsKey(new TestInteger(3)));
    }

    /**
     * @test
     */
    public function containsValueChecking(): void
    {
        $instance = $this->createInstance();
        $instance->put(1, 1);
        $instance->put(2, 2);
        $instance->put(3, 3);

        $this->assertTrue($instance->containsValue(1));
        $this->assertFalse($instance->containsValue(4));
    }

    /**
     * @test
     */
    public function getting(): void
    {
        $instance = $this->createInstance();
        $instance->put(1, 1);
        $instance->put(2, 2);
        $instance->put(3, 3);

        $this->assertNotNull($instance->get(1));
        $this->assertNull($instance->get(4));
    }

    /**
     * @test
     */
    public function usingSimpleArrayAsKey(): void
    {
        $instance = $this->createInstance();
        $instance->put([1, 2, 3], 1);
        $instance->put([1, 2, 3], 2);
        $instance->put([1, 3, 3], 3);

        $this->assertEquals(2, $instance->size());
        $this->assertTrue($instance->containsKey([1, 2, 3]));
        $this->assertTrue($instance->containsKey([1, 3, 3]));
    }

    /**
     * @test
     */
    public function removingOfAbsent(): void
    {
        $instance = $this->createInstance();
        $instance->put(1, 1);
        $instance->put(2, 2);
        $instance->put(3, 3);

        $this->assertFalse($instance->remove(4));
    }

    /**
     * @test
     */
    public function usingFunctionAsKey(): void
    {
        $instance = $this->createInstance();
        $instance->put(static function () {}, null);
        $this->assertFalse($instance->containsKey(static function () {}));
    }

    /**
     * @test
     */
    public function unsupportedKeyType(): void
    {
        $this->expectException(Exception::class);
        /** @var Map $map */
        $map = $this->createInstance();
        $f = null;
        try {
            $f = fopen(__FILE__, 'rb');
            $map->put($f, null);
        } catch (Exception $exception) {
            fclose($f);
            /** @noinspection PhpUnhandledExceptionInspection */
            throw $exception;
        }
    }
}
