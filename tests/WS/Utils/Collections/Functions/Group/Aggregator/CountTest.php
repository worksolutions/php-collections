<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use PHPUnit\Framework\TestCase;

class CountTest extends TestCase
{

    public function cases(): array
    {
        return [
            [
                [
                    ['one' => 'asdf'],
                    [],
                    [],
                    ['one' => 999],
                    ['one' => false],
                    ['one' => true],
                ],
                6
            ],
            [
                [
                    ['count' => 1],
                    ['count' => 2],
                    ['count' => 3],
                ],
                3
            ],
            [
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
                4
            ],
            [
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
                3
            ],
        ];
    }

    /**
     * @dataProvider cases
     * @test
     * @param $collection
     * @param $expected
     */
    public function callSuccess($collection, $expected)
    {
        $aggregator = new Count();
        $this->assertEquals($expected, $aggregator($collection));
    }

}
