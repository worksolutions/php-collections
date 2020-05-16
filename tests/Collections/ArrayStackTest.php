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
    
    public function testSize(): void
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
    
    public function testEmptiness(): void
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
    
    public function testPeek(): void
    {
        $stack = new ArrayStack([1, 2, 3]);
        $this->assertEquals(3, $stack->peek());
    }
    
    public function testPeekEmpty(): void
    {
        $this->expectException(\RuntimeException::class);

        $stack = new ArrayStack([]);
        $stack->peek();
    }
    
    public function testPush(): void
    {
        $stack = new ArrayStack([100, 0, 50]);

        $this->assertTrue($stack->push(10));
        $this->assertTrue($stack->push(10));
        $this->assertEquals(5, $stack->size());
        $this->assertTrue($stack->push(15));
        $this->assertEquals(15, $stack->peek());
        $this->assertEquals(6, $stack->size());
    }
    
    public function testAdd(): void
    {
        $stack = new ArrayStack(['a', 'b', 'c']);

        $this->assertTrue($stack->add(10));
        $this->assertTrue($stack->add(10));
        $this->assertEquals(5, $stack->size());
        $this->assertTrue($stack->add('abc'));
        $this->assertEquals('abc', $stack->peek());
        $this->assertEquals(6, $stack->size());
    }
    
    public function testPop(): void
    {
        $stack = new ArrayStack([100, 0, '50', 'asd', 20]);

        $this->assertEquals(20, $stack->pop());
        $this->assertEquals('asd', $stack->pop());
        $this->assertEquals('50', $stack->pop());
        $this->assertEquals(0, $stack->pop());
        $this->assertEquals(100, $stack->pop());
    }

    public function testPopEmpty(): void
    {
        $this->expectException(\RuntimeException::class);

        $stack = new ArrayStack([]);
        $stack->pop();
    }

    public function testToArray(): void
    {
        $stack = new ArrayStack([1, 2, 3]);
        $this->assertEquals([3, 2, 1], $stack->toArray());
        $stack->pop();
        $this->assertEquals([2, 1], $stack->toArray());
        $stack->add(1);
        $this->assertEquals([1, 2, 1], $stack->toArray());
    }

    public function testClear(): void
    {
        $stack = new ArrayStack([1, 2, 3]);
        $stack->clear();
        $this->assertEmpty($stack->toArray());
    }

    public function testGetIterator(): void
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

    public function testMerge(): void
    {
        $stack1 = new ArrayStack([1, 7]);
        $stack2 = new ArrayStack(['c', 'a']);

        $stack1->merge($stack2);
        $this->assertEquals(['c', 'a', 7, 1], $stack1->toArray());

        $stack2->merge($stack2);
        $this->assertEquals(['c', 'a', 'a', 'c'], $stack2->toArray());
    }

    public function testContains(): void
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

    public function testRemove(): void
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

    public function testEquals(): void
    {
        $stack1 = new ArrayStack([1, 7]);
        $stack2 = new ArrayStack([1, 7]);

        $this->assertTrue($stack1->equals($stack2));
        $this->assertTrue($stack2->equals($stack1));
    }

    public function testNotEquals(): void
    {
        $stack1 = new ArrayStack([1, 7]);
        $stack2 = new ArrayStack([7, 1]);
        $stack3 = new ArrayStack([1, 7, 7]);
        $stack4 = new ArrayStack(['1', '7']);

        $this->assertFalse($stack1->equals($stack2));
        $this->assertFalse($stack1->equals($stack3));
        $this->assertFalse($stack1->equals($stack4));
        $this->assertFalse($stack2->equals($stack3));
    }

}