<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Expression;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\CollectionFactory;

class BoolExpressionTest extends TestCase
{

    public function cases()
    {
        return [
            [
                [1, 2, 3],
                BoolExpression::with(function ($element) {
                    return $element !== 2;
                })
                    ->or(function ($element) {
                        return $element !== 77;
                    })
                    ->and(function ($element) {
                        return in_array($element, [1, 3]);
                    }),
                [1, 3],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider cases
     * @param array $sequence
     * @param BoolExpression $expression
     * @param array $expected
     */
    public function filterByExpression(array $sequence, BoolExpression $expression, array $expected)
    {

        $result = CollectionFactory::from($sequence)
            ->stream()
            ->filter($expression)
            ->getCollection()
            ->toArray();

        self::assertEquals($expected, $result);
    }

}
