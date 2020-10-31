<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use PHPUnit\Framework\TestCase;

class SumTest extends TestCase
{

    public function cases(): array
    {
        return [
            [
                'price',
                [],
                null,
            ],
            [
                'price',
                [[], [], []],
                null,
            ],
            [
                'price',
                [
                    ['price' => 0],
                    [],
                    ['price' => 23],
                    [],
                    ['price' => 10],
                ],
                33
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
                15
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
                        public $test = -3;
                    },
                    new class () {
                        public $test = 4;
                    },
                    new class () {
                        public $test = 15;
                    },
                ],
                19
            ],
            [
                'sum',
                [
                    new class () {
                        public function getSum() {
                            return 10;
                        }
                    },
                    new class () {
                        public function getSum() {
                            return 20;
                        }
                    },
                ],
                30
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
        $aggregator = new Sum($column);
        $this->assertEquals($expected, $aggregator($collection));
    }

}
