<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class StrImplodeAggregatorTest extends TestCase
{

    use CollectionAwareTrait;

    /**
     * @test
     */
    public function isCallable(): void
    {
        $f = Collectors::concat();
        $this->assertIsCallable($f);
    }

    public function implodeCases(): array
    {
        return [
            [[1, 2, 3], ', ', '1, 2, 3'],
            [[], '1111', ''],
            [[1], '|', '1'],
            [null, '', '']
        ];
    }

    /**
     * @dataProvider implodeCases
     * @test
     * @param $data
     * @param $glue
     * @param $result
     */
    public function imploding($data, $glue, $result): void
    {
        $f = Collectors::concat($glue);
        $this->assertSame($f($this->toCollection($data)), $result);
    }
}
