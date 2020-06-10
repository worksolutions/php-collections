<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Aggregators;
use WS\Utils\Collections\Functions\Comparators;
use WS\Utils\Collections\Utils\ExampleObject;

class ComparatorsTest extends TestCase
{
    public function scalarComparatorCases(): array
    {
        return [
            //a|b|expected
            [1, 2, -1],
            [2, 1, 1],
            [2, null, 1],
            [2, '4', -1],
            ['abc', 'cba', -1],
            ['cba', 'abc', 1],
        ];
    }

    /**
     * @dataProvider scalarComparatorCases
     * @test
     */
    public function scalarComparatorChecking($a, $b, $expected): void
    {
        $comparator = Comparators::scalarComparator();
        $this->assertEquals($comparator($a, $b), $expected);
    }

    public function objectComparatorTestCases(): array
    {
        $sequence = [];

        for ($i = 0; $i < 10; $i++) {
            $obj = new ExampleObject();
            $sequence[] = $obj;
            $obj->property = $i;
            $obj->setField($i);
            $obj->setName($i);
        }

        $cases = [];
        for ($i = 0; $i < 2; $i++) {
            $shuffledSequence = (new ArrayList($sequence))
                ->stream()
                ->aggregate(Aggregators::shuffle())
                ->toArray()
            ;
            $cases[] = [$shuffledSequence, 'property', $sequence];
        }
        for ($i = 0; $i < 2; $i++) {
            $shuffledSequence = (new ArrayList($sequence))
                ->stream()
                ->aggregate(Aggregators::shuffle())
                ->toArray()
            ;
            $cases[] = [$shuffledSequence, 'name', $sequence];
        }
        for ($i = 0; $i < 2; $i++) {
            $shuffledSequence = (new ArrayList($sequence))
                ->stream()
                ->aggregate(Aggregators::shuffle())
                ->toArray()
            ;
            $cases[] = [$shuffledSequence, 'field', $sequence];
        }

        return $cases;
    }

    /**
     * @dataProvider objectComparatorTestCases
     * @test
     * @param $sequence
     * @param $field
     * @param $expected
     */
    public function objectComparatorChecking($sequence, $field, $expected): void
    {
        $actual = (new ArrayList($sequence))
            ->stream()
            ->sort(Comparators::objectFieldComparator($field))
            ->getCollection()
            ->toArray();

        $this->assertEquals($expected, $actual);
    }
}
