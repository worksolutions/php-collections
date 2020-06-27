<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class ArrayStackTest extends TestCase
{
    use CollectionInterfaceTestTrait;

    private function createInstance(...$args): Stack
    {
        return new ArrayStack($args);
    }

    /**
     * @test
     */
    public function peeking(): void
    {
        $stack = ArrayStack::of(1, 2, 3);
        $this->assertEquals(3, $stack->peek());
        $this->assertEquals(3, $stack->size());
    }

    /**
     * @test
     */
    public function peekingEmpty(): void
    {
        $this->expectException(RuntimeException::class);

        $stack = new ArrayStack();
        $stack->peek();
    }

    /**
     * @test
     */
    public function pushing(): void
    {
        $stack = ArrayStack::of(100, 0, 50);

        $this->assertTrue($stack->push(10));
        $this->assertTrue($stack->push(10));
        $this->assertEquals(5, $stack->size());
        $this->assertTrue($stack->push(15));
        $this->assertEquals(15, $stack->peek());
        $this->assertEquals(6, $stack->size());
    }

    /**
     * @test
     */
    public function popping(): void
    {
        $stack = ArrayStack::of(100, 0, '50', 'asd', 20);

        $this->assertEquals(20, $stack->pop());
        $this->assertEquals('asd', $stack->pop());
        $this->assertEquals('50', $stack->pop());
        $this->assertEquals(0, $stack->pop());
        $this->assertEquals(100, $stack->pop());
    }

    /**
     * @test
     */
    public function emptyPopping(): void
    {
        $this->expectException(RuntimeException::class);

        $stack = new ArrayStack();
        $stack->pop();
    }

    /**
     * @test
     */
    public function iterating(): void
    {
        $stack = ArrayStack::of(9, 8, 'a', 'n');

        $elements = [];
        /** @noinspection PhpUnhandledExceptionInspection */
        foreach ($stack->getIterator() as $element) {
            $elements[] = $element;
        }
        $this->assertEquals(['n', 'a', 8, 9], $elements);

        $elements = [];
        /** @noinspection PhpUnhandledExceptionInspection */
        foreach ($stack->getIterator() as $element) {
            $elements[] = $element;
        }
        $this->assertEquals(['n', 'a', 8, 9], $elements);
    }

    /**
     * @test
     */
    public function merging(): void
    {
        $stack1 = ArrayStack::of(1, 7);
        $stack2 = ArrayStack::of('c', 'a');

        $stack1->merge($stack2);
        $this->assertEquals(['c', 'a', 7, 1], $stack1->toArray());

        $stack2->merge($stack2);
        $this->assertEquals(['c', 'a', 'a', 'c'], $stack2->toArray());
    }

    /**
     * @test
     */
    public function equivalency(): void
    {
        $stack1 = ArrayStack::of(1, 7);
        $stack2 = ArrayStack::of(1, 7);

        $this->assertTrue($stack1->equals($stack2));
        $this->assertTrue($stack2->equals($stack1));
    }

    /**
     * @test
     */
    public function absentEquivalency(): void
    {
        $stack1 = ArrayStack::of(1, 7);
        $stack2 = ArrayStack::of(7, 1);
        $stack3 = ArrayStack::of(1, 7, 7);
        $stack4 = ArrayStack::of('1', '7');

        $this->assertFalse($stack1->equals($stack2));
        $this->assertFalse($stack1->equals($stack3));
        $this->assertFalse($stack1->equals($stack4));
        $this->assertFalse($stack2->equals($stack3));
    }
}
