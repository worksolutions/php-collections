<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class RandomReorganizerTest extends TestCase
{

    use CollectionAwareTrait;

    public function cases(): array
    {
        return [
            [ [1, 2, 3, 4, 5], 5],
            [ [1, 2, 3, 3, 4, 4, 5], 5],
            [ [1, 2, 3, 3, 4, 4, 5], 0],
            [ [1, 2, 3, 3, 4, 4, 5], 1],
            [ [1, 2, 3, 3, 4, 4, 5], 10],
        ];
    }

    /**
     * @dataProvider cases
     * @test
     * @param array $elements
     * @param int $count
     */
    public function test(array $elements, int $count): void
    {
        $f = Reorganizers::random($count);
        $randomized = $f($this->toCollection($elements))->toArray();

        $this->analyze($elements, $randomized, $count);
    }

    /**
     * @dataProvider cases
     * @test
     * @param array $elements
     * @param int $count
     */
    public function integrate(array $elements, int $count): void
    {
        $randomized = $this->toCollection($elements)
            ->stream()
            ->reorganize(Reorganizers::random($count))
            ->getCollection()
            ->toArray()
        ;

        $this->analyze($elements, $randomized, $count);
    }

    private function analyze(array $input, array $randomized, int $count): void
    {
        $this->assertTrue(count($input) >= count($randomized));
        $this->assertTrue(count($randomized) <= $count);
        if (count($input) >= $count) {
            $this->assertCount($count, $randomized);
        }
    }
}
