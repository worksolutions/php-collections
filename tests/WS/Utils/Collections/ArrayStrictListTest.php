<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ArrayStrictListTest extends TestCase
{
    public function createInstance(...$args): ListSequence
    {
        return ArrayStrictList::of(...$args);
    }

    public function strictCases(): array
    {
        return [
            [1, 2, 3],
            [1.1, 2.2, 3.3],
            ['one', 'two', 'three'],
            [true, false, true],
            [[1], ['2'], [3.3]],
            [null, null, null],
            [
                function () {
                    return '1';
                },
                function () {
                    return 'two';
                },
                function () {
                    return 3;
                },
            ],
            [
                $object = new class () {
                },
                clone $object,
                clone $object,
            ],
        ];
    }

    public function notStrictCases(): array
    {
        return [
            [1, '2', 3],
            [1.1, 2.2, 3],
            ['one', 'two', 3.3],
            ['true', false, true],
            [[1], null, [3.3]],
            [null, null, []],
            [
                function () {
                    return '1';
                },
                new class () {
                },
                function () {
                    return 3;
                },
            ],
            [
                $object = new class () {
                },
                clone $object,
                new class () {
                },
            ],
        ];
    }

    /**
     * @test
     * @dataProvider strictCases
     * @doesNotPerformAssertions
     * @param $sequence
     */
    public function creatingFromStrict(...$sequence): void
    {
        $this->createInstance(...$sequence);
    }

    /**
     * @test
     * @dataProvider notStrictCases
     * @param $sequence
     */
    public function creatingFromNotStrict(...$sequence): void
    {
        self::expectException(InvalidArgumentException::class);
        $this->createInstance(...$sequence);
    }
}
