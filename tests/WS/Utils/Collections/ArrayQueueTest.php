<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use RuntimeException;

class ArrayQueueTest extends TestCase
{

    public function testToArray()
    {
        $queueProvider = [100, 0, 20, 10, 9];
        $queue = ArrayQueue::of(...$queueProvider);
        $this->assertEquals($queueProvider, $queue->toArray());
    }

    public function testOffer()
    {
        $queueProvider = [100, 0, 20, 10, 9];
        $queue = ArrayQueue::of(...$queueProvider);
        $this->assertTrue($queue->offer(100));
        $this->assertTrue($queue->offer(100));
        $this->assertTrue($queue->offer(101));
        $this->assertEquals(count($queueProvider) + 3, $queue->size());
        $this->assertEquals(array_merge($queueProvider, [100, 100, 101]), $queue->toArray());
    }

    public function testPoll()
    {
        $queueProvider = [100, 0, 20, 10, 9];
        $queue = ArrayQueue::of(...$queueProvider);

        $this->assertEquals(100, $queue->poll());
        $this->assertEquals(count($queueProvider) - 1, $queue->size());
        $this->assertEquals(0, $queue->poll());
        $this->assertEquals(20, $queue->poll());
        $this->assertEquals(count($queueProvider) - 3, $queue->size());
    }

    public function testPollEmpty()
    {
        $this->expectException(RuntimeException::class);
        $queueProvider = [];
        $queue = ArrayQueue::of(...$queueProvider);
        $queue->poll();
    }

    public function testPeek()
    {
        $queueProvider = [100, 0, 20, 10, 9];
        $queue = ArrayQueue::of(...$queueProvider);

        $this->assertEquals(100, $queue->peek());
        $this->assertEquals(count($queueProvider), $queue->size());
        $this->assertEquals(100, $queue->peek());
        $this->assertEquals(100, $queue->peek());
        $this->assertEquals(count($queueProvider), $queue->size());
        $queue->poll();
        $queue->poll();
        $this->assertEquals(20, $queue->peek());
    }

    public function testPeekEmpty()
    {
        $this->expectException(RuntimeException::class);
        $queueProvider = [];
        $queue = ArrayQueue::of(...$queueProvider);
        $queue->peek();
    }

    public function testAll() {
        $queue = ArrayQueue::of();
        $queue->add(2);
        $queue->add(3);
        $queue->add(1);

        $this->assertEquals(3, $queue->size());

        $this->assertEquals(2, $queue->peek());
        $this->assertEquals(2, $queue->poll());
        $queue->add(10);
        $this->assertFalse($queue->isEmpty());
        $this->assertEquals(3, $queue->peek());
        $this->assertEquals(3, $queue->poll());
        $this->assertEquals(1, $queue->poll());
        $this->assertEquals(10, $queue->poll());
        $this->assertEquals(0, $queue->size());
        $this->assertTrue($queue->isEmpty());
    }
}
