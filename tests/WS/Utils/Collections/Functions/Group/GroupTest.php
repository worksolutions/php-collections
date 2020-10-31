<?php

namespace WS\Utils\Collections\Functions\Group;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\CollectionFactory;

class GroupTest extends TestCase
{

    public function arrayCases()
    {
        return [
            [
                [
                    [
                        'one' => 1,
                        'groupKey' => 'test',
                        'two' => 2,
                        'other' => 11,
                    ],
                ],
                [
                    'test' => [
                        [
                            'one' => 1,
                            'groupKey' => 'test',
                            'two' => 2,
                            'other' => 11,
                        ]
                    ],
                ]
            ],
            [
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
                    'test' => [
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
                    ],
                    'test1' => [
                        [
                            'three' => 3,
                            'groupKey' => 'test1',
                            'four' => 4,
                        ],
                    ],
                ]
            ],
            [
                [
                    [
                        'groupKey' => 'test',
                        'one' => 1,
                        'two' => 2,
                        'other' => 11,
                    ],
                    'testKey' => 12,
                    [
                        'three' => 3,
                        'groupKey' => 'test1',
                        'four' => 4,
                    ],
                    [],
                    5,
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
                    'test' => [
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

                    ],
                    'test1' => [
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
                    ],
                    67 => [
                        [
                            'groupKey' => 67,
                        ],
                    ],
                ]
            ],
        ];
    }

    /**
     * @dataProvider arrayCases
     * @test
     * @param array $values
     * @param array $expected
     */
    public function groupTest(array $values, array $expected)
    {
        $result = CollectionFactory::from($values)
            ->stream()
            ->collect(Group::by('groupKey'));

        $this->assertEquals($expected, $result);
    }


}
