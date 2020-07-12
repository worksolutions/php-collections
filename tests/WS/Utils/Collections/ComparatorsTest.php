<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Comparators;
use WS\Utils\Collections\Functions\Reorganizers;
use WS\Utils\Collections\UnitConstraints\CollectionIsEqual;
use WS\Utils\Collections\UnitConstraints\CollectionIsNotEqual;
use WS\Utils\Collections\Utils\ExampleObject;
use WS\Utils\Collections\Utils\InvokeCounter;

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
     * @param $a
     * @param $b
     * @param $expected
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
                ->collect(Reorganizers::shuffle())
                ->toArray()
            ;
            $cases[] = [$shuffledSequence, 'property', $sequence];
        }
        for ($i = 0; $i < 2; $i++) {
            $shuffledSequence = (new ArrayList($sequence))
                ->stream()
                ->collect(Reorganizers::shuffle())
                ->toArray()
            ;
            $cases[] = [$shuffledSequence, 'name', $sequence];
        }
        for ($i = 0; $i < 2; $i++) {
            $shuffledSequence = (new ArrayList($sequence))
                ->stream()
                ->collect(Reorganizers::shuffle())
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
            ->sort(Comparators::objectPropertyComparator($field))
            ->getCollection()
            ->toArray();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider scalarComparatorCases
     * @test
     * @param $a
     * @param $b
     * @param $expected
     */
    public function callbackComparatorChecking($a, $b, $expected): void
    {
        $f = new InvokeCounter(static function ($value) {
            return $value;
        });
        $comparator = Comparators::callbackComparator($f);
        $this->assertEquals($comparator($a, $b), $expected);
        $this->assertTrue($f->countOfInvokes() > 0);
    }

    /**
     * @test
     */
    public function integrateCallbackComparatorChecking(): void
    {
        $f = new InvokeCounter(static function ($value) {
            return $value;
        });

        $sourceCollection = CollectionFactory::numbers(10);
        $shuffledCollection = $sourceCollection
            ->stream()
            ->reorganize(Reorganizers::shuffle())
            ->getCollection();

        $this->assertThat($sourceCollection, CollectionIsNotEqual::to($shuffledCollection));

        $sortedCollection = $shuffledCollection->stream()
            ->sort(Comparators::callbackComparator($f))
            ->getCollection()
        ;
        $this->assertThat($sourceCollection, CollectionIsEqual::to($sortedCollection));
    }
}
