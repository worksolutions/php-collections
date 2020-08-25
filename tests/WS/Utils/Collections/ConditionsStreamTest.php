<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\MockObject\Matcher\InvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Predicates;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\Utils\InvokeCounter;

class ConditionsStreamTest extends TestCase
{

    /**
     * @test
     */
    public function gettingDummyStream(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(false)
            ->filter(Predicates::lessThan(4))
            ->getCollection()
        ;
        self::assertEquals(10, $collection->size());
    }

    /**
     * @test
     */
    public function keepingOriginalStream(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->always()
            ->filter(Predicates::eachEven())
            ->getCollection()
        ;

        self::assertEquals(5, $collection->size());
    }

    /**
     * @test
     */
    public function obtainNormalStreamFromDummy(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(false)
            ->filter(Predicates::lessOrEqual(5))
            ->when(true)
            ->filter(Predicates::greaterOrEqual(5))
            ->getCollection()
        ;

        self::assertThat($collection, CollectionIsEqual::to([5, 6, 7, 8, 9]));
    }

    /**
     * @test
     */
    public function obtainNormalStreamWithAlwaysCondition(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(false)
            ->filter(Predicates::lessOrEqual(5))
            ->always()
            ->filter(Predicates::greaterOrEqual(5))
            ->getCollection()
        ;

        self::assertThat($collection, CollectionIsEqual::to([5, 6, 7, 8, 9]));
    }

    /**
     * @test
     */
    public function dontObtainNormalStreamFromDummy(): void
    {
        $sourceCollection = CollectionFactory::numbers(10);
        $resultCollection = $sourceCollection
            ->stream()
            ->when(false)
            ->filter(Predicates::lessOrEqual(5))
            ->when(false)
            ->filter(Predicates::greaterOrEqual(5))
            ->getCollection()
        ;

        self::assertThat($resultCollection, CollectionIsEqual::to($sourceCollection));
    }

    /**
     * @test
     */
    public function usingWithoutDummyDecorator(): void
    {
        $collection = CollectionFactory::numbers(10)
            ->stream()
            ->when(true)
            ->filter(Predicates::lessOrEqual(6))
            ->when(true)
            ->filter(Predicates::greaterOrEqual(4))
            ->getCollection()
        ;

        self::assertThat($collection, CollectionIsEqual::to([4, 5, 6]));
    }

    public function streamModifiers(): array
    {
        return [
            ['each', new InvokeCounter()],
            ['walk', new InvokeCounter()],
            ['filter', new InvokeCounter()],
            ['reorganize', new InvokeCounter()],
            ['map', new InvokeCounter()],
            ['sort', new InvokeCounter()],
            ['sortBy', new InvokeCounter()],
            ['sortDesc', new InvokeCounter()],
            ['sortByDesc', new InvokeCounter()],
            ['reverse'],
            ['limit', 2]
        ];
    }

    /**
     * @dataProvider streamModifiers
     * @test
     * @param $method
     * @param mixed ...$args
     */
    public function shouldNotModifyStream($method, ...$args): void
    {
        $stream = CollectionFactory::numbers(10)
            ->stream()
            ->when(false)
        ;
        $sourceCollection = $stream
            ->getCollection();

        call_user_func_array([$stream, $method], $args);

        foreach ($args as $arg) {
            if ($arg instanceof InvokeCounter && $arg->countOfInvokes() > 0) {
                self::fail("Modifier callback shouldn't be called");
            }
        }

        self::assertThat($sourceCollection, CollectionIsEqual::to($stream->getCollection()));
    }

    public function dummyStreamWrapperMethods(): array
    {
        $f = static function() {};
        return [
            ['allMatch', $f],
            ['anyMatch', $f],
            ['collect', $f],
            ['findAny', $f],
            ['findFirst', $f],
            ['findLast', $f],
            ['min', $f],
            ['max', $f],
            ['reduce', $f],
            ['getCollection']
        ];
    }

    /**
     * @dataProvider dummyStreamWrapperMethods
     * @test
     * @param $method
     * @param array $args
     */
    public function dummyStreamAsWrapper($method, ...$args): void
    {
        /** @var Stream|MockObject $mockStream */
        $mockStream = $this->getMockBuilder(Stream::class)
            ->getMock()
        ;

        $mockStream
            ->expects(new InvokedCount(1))
            ->method($method)
        ;

        $dummyStream = new DummyStreamDecorator($mockStream);
        call_user_func_array([$dummyStream, $method], $args);
    }
}
