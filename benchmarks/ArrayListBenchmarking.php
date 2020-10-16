<?php
/**
 * @author Maxim Sokolovsky
 */

namespace Benchmarks;

use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use WS\Utils\Collections\ArrayList;
use WS\Utils\Collections\ListSequence;

/**
 * @BeforeMethods({"init"})
 * Class ListBenchmarking
 * @package Benchmarks
 */
class ArrayListBenchmarking
{
    use CollectionBenchmarkingTrait;

    private $bigArray = [];
    /** @var ListSequence */
    private $bigList;
    /**
     * @var ListSequence
     */
    private $smallList;
    private $smallArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    public function init(): void
    {
        $this->bigArray = range(0, 10000);
        $this->bigList = $this->constructor(range(0, 10000));
        $this->smallList = $this->constructor($this->smallArray);
    }

    protected function getSmallArray(): array
    {
        return $this->smallArray;
    }

    protected function getBigArray(): array
    {
        return $this->bigArray;
    }

    protected function getBigCollection()
    {
        return $this->bigList;
    }

    protected function getSmallCollection()
    {
        return $this->smallList;
    }

    protected function constructor(array $elements)
    {
        return new ArrayList($elements);
    }
}
