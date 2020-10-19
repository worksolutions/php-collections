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
    abstract protected function createInstance(): Map;

    /**
     * @test
     */
    public function checkSameObjectAsKey(): void
    {
        $obj1 = $obj2 = new SplObjectStorage();
        $instance = $this->createInstance();
        $instance->put($obj1, null);
        self::assertTrue($instance->containsKey($obj2));

        self::assertNull($instance->get($obj2));
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

        self::assertEquals(4, $instance->size());
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
            self::assertSame($k, $v - 1);
            self::assertSame($i, $k);
            $i++;
        }
    }

    /**
     * @test
     */
    public function keySetGetting(): void
    {
        $instance = $this->createInstance();
        $instance->put(1,1);
        $instance->put(2,1);
        $instance->put(2,1);
        $instance->put(3,1);
        $instance->put(4,1);
        $instance->put(null,1);

        $instance->remove(4);

        $set = $instance->keys();

        self::assertEquals(4, $set->size());
    }

    /**
     * @test
     */
    public function valuesGetting(): void
    {
        $instance = $this->createInstance();
        $instance->put(1,1);
        $instance->put(2,1);
        $instance->put(2,1);
        $instance->put(3,1);
        $instance->put(4,1);
        $instance->put(5,1);

        $instance->remove(4);

        $values = $instance->values();

        self::assertEquals(4, $values->size());
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

        self::assertTrue($instance->containsKey(new TestInteger(1)));
        self::assertTrue($instance->containsKey(new TestInteger(2)));
        self::assertTrue($instance->containsKey(new TestInteger(3)));
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

        self::assertTrue($instance->containsValue(1));
        self::assertFalse($instance->containsValue(4));
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

        self::assertNotNull($instance->get(1));
        self::assertNull($instance->get(4));
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

        self::assertEquals(2, $instance->size());
        self::assertTrue($instance->containsKey([1, 2, 3]));
        self::assertTrue($instance->containsKey([1, 3, 3]));
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

        self::assertFalse($instance->remove(4));
    }

    /**
     * @test
     */
    public function usingFunctionAsKey(): void
    {
        $instance = $this->createInstance();
        $instance->put(static function () {}, null);
        self::assertFalse($instance->containsKey(static function () {}));
    }

    /**
     * @test
     */
    public function unsupportedKeyType(): void
    {
        $this->expectException(Exception::class);
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

    /**
     * @test
     */
    public function foreachObjectKeyValueChecking(): void
    {
        $map = $this->createInstance();

        $map->put(new SplObjectStorage(), 1);
        $map->put(new SplObjectStorage(), 2);
        $map->put(new SplObjectStorage(), 3);

        foreach ($map as $splObjectStorage => $intValue) {
            self::assertThat($splObjectStorage, self::isInstanceOf(SplObjectStorage::class));
        }
    }

    /**
     * @test
     */
    public function foreachAnyKeyValueChecking(): void
    {
        $map = $this->createInstance();

        $map->put(null, 1);
        $map->put(false, 2);
        $map->put(true, 3);

        foreach ($map as $k => $int) {
            switch ($int) {
                case 1:
                    self::assertThat($k, self::isNull());
                    break;
                case 2:
                    self::assertThat($k, self::isFalse());
                    break;
                case 3:
                    self::assertThat($k, self::isTrue());
                    break;
            }
        }
    }

    /**
     * @test
     */
    public function streamGetting(): void
    {
        $map = $this->createInstance();

        $map->put('1', 1);
        $map->put('2', 2);
        $map->put('3', 3);

        $stream = $map->stream();
        self::assertThat($stream, self::isInstanceOf(Stream::class));

        self::assertGreaterThan(0, $stream->getCollection()->size());

        $stream
            ->each(static function (MapEntry $mapEntry) {
                self::assertThat($mapEntry->getKey() === ''.$mapEntry->getValue(), self::isTrue());
            });
    }
}
