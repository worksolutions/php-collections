<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class AddToSetTest extends TestCase
{

    use CollectionAwareTrait;

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
                ['asdf', null, 999, false, true]
            ],
            [
                'count',
                [
                    ['count' => 1],
                    ['count' => 2],
                    ['count' => 3],
                ],
                [1, 2, 3]
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
                [1, 15]
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
                [10, 20]
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
        $aggregator = new AddToSet($column);
        $this->assertEquals($expected, $aggregator($this->toCollection($collection)));
    }

}
