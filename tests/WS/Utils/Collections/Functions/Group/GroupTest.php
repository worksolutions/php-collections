<?php

namespace WS\Utils\Collections\Functions\Group;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\Group\Aggregator\Count;

class GroupTest extends TestCase
{

    public function arrayCases(): array
    {
        return [
            [
                'groupKey',
                [
                    [
                        'one' => 1,
                        'groupKey' => 'test',
                        'two' => 2,
                        'other' => 11,
                    ],
                ],
                [
                    'test' => CollectionFactory::from(
                        [
                            [
                                'one' => 1,
                                'groupKey' => 'test',
                                'two' => 2,
                                'other' => 11,
                            ]
                        ]
                    ),
                ]
            ],
            [
                'groupKey',
                [
                    [
                        'groupKey' => 'test',
                        'one' => 1,
                        'two' => 2,
                        'other' => 11,
                    ],
                    [
                        'three' => 3,
                        'groupKey' => 'test1',
                        'four' => 4,
                    ],
                    [
                        'five' => 5,
                        'groupKey' => 'test',
                        'six' => 6,
                        'other' => 15,
                    ],
                ],
                [
                    'test' => CollectionFactory::from(
                        [
                            [
                                'one' => 1,
                                'groupKey' => 'test',
                                'two' => 2,
                                'other' => 11,
                            ],
                            [
                                'five' => 5,
                                'groupKey' => 'test',
                                'six' => 6,
                                'other' => 15,
                            ],
                        ]
                    ),
                    'test1' => CollectionFactory::from(
                        [
                            [
                                'three' => 3,
                                'groupKey' => 'test1',
                                'four' => 4,
                            ],
                        ]
                    ),
                ]
            ],
            [
                'groupKey',
                [
                    [
                        'groupKey' => 'test',
                        'one' => 1,
                        'two' => 2,
                        'other' => 11,
                    ],
                    [
                        'three' => 3,
                        'groupKey' => 'test1',
                        'four' => 4,
                    ],
                    [],
                    [
                        'five' => 5,
                        'groupKey' => 'test',
                        'six' => 6,
                        'other' => 15,
                    ],
                    24 => [],
                    90 => [
                        'seven' => 7,
                        'eight' => 8,
                        'groupKey' => 'test',
                    ],
                    '67' => [
                        'nine' => '9',
                        'ten' => 10,

                    ],
                    'testItem' => [
                        'eleven' => '11',
                        'groupKey' => 'test1',
                        'twelve' => 12,
                    ],
                    [
                        'groupKey' => 67,
                    ]
                ],
                [
                    'test' => CollectionFactory::from(
                        [
                            [
                                'groupKey' => 'test',
                                'one' => 1,
                                'two' => 2,
                                'other' => 11,
                            ],
                            [
                                'five' => 5,
                                'groupKey' => 'test',
                                'six' => 6,
                                'other' => 15,
                            ],
                            [
                                'seven' => 7,
                                'eight' => 8,
                                'groupKey' => 'test',
                            ]

                        ]
                    ),
                    'test1' => CollectionFactory::from(
                        [
                            [
                                'three' => 3,
                                'groupKey' => 'test1',
                                'four' => 4,
                            ],
                            [
                                'eleven' => '11',
                                'groupKey' => 'test1',
                                'twelve' => 12,
                            ],
                        ]
                    ),
                    67 => CollectionFactory::from(
                        [
                            [
                                'groupKey' => 67,
                            ],
                        ]
                    ),
                ]
            ],
        ];
    }

    public function objectCases(): array {
        return [
            [
                'groupProperty',
                [
                    $object1 = new class () {
                        public function getGroupProperty()
                        {
                            return 10;
                        }
                    },
                ],
                [
                    10 => CollectionFactory::from(
                        [
                            $object1
                        ]
                    ),
                ]
            ],
            [
                'groupProperty',
                [
                    $object1 = new class () {
                        public function getGroupProperty()
                        {
                            return 11;
                        }
                    },
                    $object2 = new class () {
                        public $groupProperty = 11;
                    },
                    $object3 = new class () {
                        public $groupProperty = '77';

                        public function getAnotherProperty()
                        {
                            return false;
                        }
                    },
                    $object4 = new class () {

                        public function getGroupProperty()
                        {
                            return 77;
                        }
                    },
                    $object5 = new class () {
                        public $groupProperty = 'testKey';
                    }
                ],
                [
                    11 => CollectionFactory::from(
                        [
                            $object1,
                            $object2,
                        ]
                    ),
                    77 => CollectionFactory::from(
                        [
                            $object3,
                            $object4,
                        ]
                    ),
                    'testKey' => CollectionFactory::from(
                        [
                            $object5
                        ]
                    ),
                ]
            ],
        ];
    }

    public function aggregateCases(): array
    {
        return [
            [
                'groupKey',
                [
                    [
                        'firstKey' => 11,
                        'groupKey' => 'test',
                        'secondKey' => 78,
                        'thirdKey' => '14',
                        'other' => 11,
                    ],
                ],
                [
                    [
                        'test' => [
                            'thirdKey' => 14,
                        ]
                    ],
                    [
                        'test' => [
                            'firstKey' => 11,
                            'secondKey' => 78,
                        ]
                    ],
                    [
                        'test' => [
                            'replacedFirstKey' => 78,
                            'thirdKey' => 14,
                            'count' => 1,
                            'replacedLastKey' => 11,
                        ]
                    ],
                ]
            ],
            [
                'groupKey',
                [
                    [
                        'firstKey' => 11,
                        'groupKey' => 'test',
                        'secondKey' => 78,
                        'thirdKey' => '14',
                        'other' => 11,
                    ],
                    new class () {
                        public $firstKey = 10;
                        public $secondKey = null;
                        public $groupKey = 'test';

                        public function getThirdKey()
                        {
                            return 190;
                        }
                    },
                ],
                [
                    [
                        'test' => [
                            'thirdKey' => 190,
                        ]
                    ],
                    [
                        'test' => [
                            'firstKey' => 10,
                            'secondKey' => 78,
                        ]
                    ],
                    [
                        'test' => [
                            'replacedFirstKey' => 78,
                            'thirdKey' => 102,
                            'count' => 2,
                            'replacedLastKey' => 10,
                        ]
                    ],
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider arrayCases
     * @dataProvider objectCases
     * @param mixed $groupKey
     * @param array $values
     * @param array $expected
     */
    public function group($groupKey, array $values, array $expected)
    {
        $result = CollectionFactory::from($values)
            ->stream()
            ->collect(Group::by($groupKey));

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * @dataProvider aggregateCases
     * @param mixed $groupKey
     * @param array $values
     * @param array $expected
     */
    public function aggregate($groupKey, array $values, array $expected)
    {
        $result = CollectionFactory::from($values)
            ->stream()
            ->collect(Group::by($groupKey)
                ->max('thirdKey')
            );

        $this->assertEquals($result, $expected[0]);

        $result = CollectionFactory::from($values)
            ->stream()
            ->collect(Group::by($groupKey)
                ->min('firstKey')
                ->sum('secondKey')
            );

        $this->assertEquals($result, $expected[1]);

        $result = CollectionFactory::from($values)
            ->stream()
            ->collect(Group::by($groupKey)
                ->first('secondKey', 'replacedFirstKey')
                ->avg('thirdKey')
                ->addAggregator('count', new Count())
                ->last('firstKey', 'replacedLastKey')
            );

        $this->assertEquals($result, $expected[2]);
    }

}
