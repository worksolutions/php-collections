<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections\Functions;

use PHPUnit\Framework\TestCase;
use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Utils\CollectionAwareTrait;

class ShuffleReorganizerTest extends TestCase
{
    use CollectionAwareTrait;

    public function cases(): array
    {
        return [
            [ [1, 2, 3] ],
            [ [1]]
        ];
    }

    /**
     * @dataProvider cases
     * @test
     */
    public function test($list): void
    {

        $this->compare($list, function ($list) {
            /** @var Collection $shuffledCollection */
            $shuffledCollection = Reorganizers::shuffle()($this->toCollection($list));

            return $shuffledCollection->toArray();
        });
    }

    /**
     * @dataProvider cases
     * @test
     */
    public function integration($list)
    {

        $this->compare($list, function ($list) {
            return $this->toCollection($list)
                ->stream()
                ->reorganize(Reorganizers::shuffle())
                ->getCollection()
                ->toArray();
        });
    }

    private function compare($list, callable $shuffle): void
    {
        $shuffled = [];
        for ($i = 0; $i < 10; $i++) {
            $shuffled = $shuffle($list);
            if ($shuffled !== $list) {
                break;
            }
            if (count($list) === 1 && count($shuffled) === 1) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->assertNotEquals($list, $shuffled);
        $this->assertCount(count($list), $shuffled);

        foreach ($list as $item) {
            $found = array_search($item, $shuffled, true);
            if ($found === false) {
                $this->fail("Item {$item} is not found in shuffled array");
            }

            $shuffled = array_diff($shuffled, [$item]);
        }
    }
}
