<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Functions\Predicates;

class PredicateFunctionTest extends TestCase
{

    public function createCollection(array $els): Collection
    {
        return new ArrayCollection($els);
    }

    public function randomDataSet(): array
    {
        return [
            [[1, 2, 3, 4, 5, 6], 2, 2],
            [[1, 2, 3, 4, 5, 6], 3, 3],
            [[1, 2, 3, 4, 5, 6], 4, 4],
            [[1, 2, 3], 5, 3],
            [[], 3, 0],
            [[1, 2, 3], 0, 0]
        ];
    }

    /**
     * @dataProvider randomDataSet
     * @test
     * @param $input
     * @param $count
     * @param $expected
     */
    public function randomPredicateChecking($input, $count, $expected): void
    {
        $randomCollection = $this->createCollection($input)
            ->stream()
            ->filter(Predicates::random($count))
            ->getCollection()
        ;
        $this->assertEquals($expected, $randomCollection->size());
    }
}