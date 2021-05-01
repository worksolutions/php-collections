<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class AvgTest extends TestCase
{

    use CollectionAwareTrait;

    public function cases(): array
    {
        return [
            [
                'count',
                [],
                null
            ],
            [
                'count',
                [
                    ['count' => 25],
                    [],
                    [],
                    [],
                    ['count' => 25],
                ],
                10
            ],
            [
                'count',
                [
                    ['count' => 1],
                    ['count' => 2],
                    ['count' => 3],
                    ['count' => 4],
                    ['count' => 5],
                ],
                3
            ],
            [
                'test',
                [
                    new class () {
                        public $test = 1;
                    },
                    new class () {
                        public $test = 2;
                    },
                    new class () {
                        public $test = 3;
                    },
                    new class () {
                        public $test = 4;
                    },
                    new class () {
                        public $test = 15;
                    },
                ],
                5
            ],
            [
                'sum',
                [
                    new class () {
                        public function getSum()
                        {
                            return 10;
                        }
                    },
                    new class () {
                        public function getSum()
                        {
                            return 20;
                        }
                    },
                ],
                15
            ],
        ];
    }

    /**
     * @dataProvider cases
     * @test
     * @param $column
     * @param $collection
     * @param $expected
     */
    public function callSuccess($column, $collection, $expected)
    {
        $aggregator = new Avg($column);
        self::assertEquals($expected, $aggregator($this->toCollection($collection)));
    }

    /**
     * @test
     */
    public function raisedException()
    {
        $this->expectException(RuntimeException::class);
        $aggregator = new Avg('test');
        $aggregator($this->toCollection([new stdClass(), new stdClass(), new stdClass()]));
    }
}
