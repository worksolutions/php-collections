<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use PHPUnit\Framework\TestCase;

class FirstTest extends TestCase
{

    public function cases(): array
    {
        return [
            [
                'one',
                [
                    ['one' => 'asdf'],
                    [],
                    [],
                    ['one' => 999],
                    ['one' => false],
                    ['one' => true],
                ],
                'asdf',
            ],
            [
                'count',
                [
                    [],
                    ['count' => 2],
                    ['count' => 3],
                ],
                null
            ],
            [
                'test',
                [
                    new class () {
                        public $test = 1;
                    },
                    new class () {
                        public $test = 1;
                    },
                    new class () {
                        public $test = 1;
                    },
                    new class () {
                        public $test = 15;
                    },
                ],
                1
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
                10
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
        $aggregator = new First($column);
        $this->assertEquals($expected, $aggregator($collection));
    }

}
