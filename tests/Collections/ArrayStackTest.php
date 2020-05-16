<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\ArrayStack;

class ArrayStackTest extends TestCase
{

    /**
     * @test
     */
    public function sizeTest(): void
    {
        $stackData = [
            [1, 2, 3],
            [],
            [0, 0, 0, 0, 0],
        ];
        foreach ($stackData as $data) {
            $stack = new ArrayStack($data);
            $this->assertEquals(count($data), $stack->size());
        }
    }

    /**
     * @test
     */
    public function emptinessTest(): void
    {
        $stack = new ArrayStack([0]);
        $this->assertFalse($stack->isEmpty());

        $stack = new ArrayStack([0, 1, -1, 30, -50]);
        $this->assertFalse($stack->isEmpty());

        $stack = new ArrayStack([]);
        $this->assertTrue($stack->isEmpty());
        $stack->push('a');
        $this->assertFalse($stack->isEmpty());
    }

    /**
     * @test
     */
    public function peekTest(): void
    {
        $stack = new ArrayStack([1, 2, 3]);
        $this->assertEquals(3, $stack->peek());
    }

    /**
     * @test
     */
    public function emptyTest(): void
    {
        $this->expectException(\RuntimeException::class);

        $stack = new ArrayStack([]);
        $stack->peek();
    }

    /**
     * @test
     */
    public function pushTest(): void
    {
        $stack = new ArrayStack([100, 0, 50]);

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
    public function addTest(): void
    {
        $stack = new ArrayStack(['a', 'b', 'c']);

        $this->assertTrue($stack->add(10));
        $this->assertTrue($stack->add(10));
        $this->assertEquals(5, $stack->size());
        $this->assertTrue($stack->add('abc'));
        $this->assertEquals('abc', $stack->peek());
        $this->assertEquals(6, $stack->size());
    }

    /**
     * @test
     */
    public function popTest(): void
    {
        $stack = new ArrayStack([100, 0, '50', 'asd', 20]);

        $this->assertEquals(20, $stack->pop());
        $this->assertEquals('asd', $stack->pop());
        $this->assertEquals('50', $stack->pop());
        $this->assertEquals(0, $stack->pop());
        $this->assertEquals(100, $stack->pop());
    }

    /**
     * @test
     */
    public function popEmptyTest(): void
    {
        $this->expectException(\RuntimeException::class);

        $stack = new ArrayStack([]);
        $stack->pop();
    }

    /**
     * @test
     */
    public function toArrayTest(): void
    {
        $stack = new ArrayStack([1, 2, 3]);
        $this->assertEquals([3, 2, 1], $stack->toArray());
        $stack->pop();
        $this->assertEquals([2, 1], $stack->toArray());
        $stack->add(1);
        $this->assertEquals([1, 2, 1], $stack->toArray());
    }

    /**
     * @test
     */
    public function clearTest(): void
    {
        $stack = new ArrayStack([1, 2, 3]);
        $stack->clear();
        $this->assertEmpty($stack->toArray());
    }

    /**
     * @test
     */
    public function getIteratorTest(): void
    {
        $stack = new ArrayStack([9, 8, 'a', 'n']);

        $elements = [];
        foreach ($stack->getIterator() as $element) {
            $elements[] = $element;
        }
        $this->assertEquals(['n', 'a', 8, 9], $elements);

        $elements = [];
        foreach ($stack->getIterator() as $element) {
            $elements[] = $element;
        }
        $this->assertEquals(['n', 'a', 8, 9], $elements);
    }

    /**
     * @test
     */
    public function mergeTest(): void
    {
        $stack1 = new ArrayStack([1, 7]);
        $stack2 = new ArrayStack(['c', 'a']);

        $stack1->merge($stack2);
        $this->assertEquals(['c', 'a', 7, 1], $stack1->toArray());

        $stack2->merge($stack2);
        $this->assertEquals(['c', 'a', 'a', 'c'], $stack2->toArray());
    }

    /**
     * @test
     */
    public function containsTest(): void
    {
        $elements = [1000, 7, 'abc', 'd'];
        $stack = new ArrayStack($elements);

        foreach ($elements as $element) {
            $this->assertTrue($stack->contains($element));
        }

        $this->assertFalse($stack->contains('not element'));
        $this->assertFalse($stack->contains(999));
        $stack->push(999);
        $this->assertTrue($stack->contains(999));
    }

    /**
     * @test
     */
    public function removeTest(): void
    {
        $stack = new ArrayStack([1, 'a', 7, 'a', 9]);

        $this->assertFalse($stack->remove(10));
        $this->assertTrue($stack->remove('a'));
        $this->assertEquals([9, 7, 'a', 1], $stack->toArray());
        $this->assertTrue($stack->remove('a'));
        $this->assertEquals([9, 7, 1], $stack->toArray());
        $this->assertTrue($stack->remove(9));
        $this->assertEquals([7, 1], $stack->toArray());
    }

    /**
     * @test
     */
    public function equalsTest(): void
    {
        $stack1 = new ArrayStack([1, 7]);
        $stack2 = new ArrayStack([1, 7]);

        $this->assertTrue($stack1->equals($stack2));
        $this->assertTrue($stack2->equals($stack1));
    }

    /**
     * @test
     */
    public function notEqualsTest(): void
    {
        $stack1 = new ArrayStack([1, 7]);
        $stack2 = new ArrayStack([7, 1]);
        $stack3 = new ArrayStack([1, 7, 7]);

        $this->assertFalse($stack1->equals($stack2));
        $this->assertFalse($stack1->equals($stack3));
        $this->assertFalse($stack2->equals($stack3));
    }

}