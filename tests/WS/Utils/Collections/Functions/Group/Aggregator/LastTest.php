<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class LastTest extends TestCase
{

    use CollectionAwareTrait;

    public function cases(): array
    {
        return [
            [
                'one',
                [],
                null,
            ],
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
                true,
            ],
            [
                'count',
                [
                    [],
                    ['count' => 2],
                    ['count' => 3],
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
                        public $test = 1;
                    },
                    new class () {
                        public $test = 1;
                    },
                    new class () {
                        public $test = 15;
                    },
                ],
                15
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
                20
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
        $aggregator = new Last($column);
        self::assertEquals($expected, $aggregator($this->toCollection($collection)));
    }

}
