<?php

namespace Benchmarks;

use Benchmarks\Assets\ValueObject;
use WS\Utils\Collections\Functions\ObjectFunctions;

/**
 * Class GettingObjectPropertyBenchmarking
 * @package Benchmarks
 */
class GettingObjectPropertyBenchmarking
{
    /**
     * @Subject
     * @Assert("10")
     * @\PhpBench\Benchmark\Metadata\Annotations\Iterations(3)
     */
    public function benchmarking()
    {
        $list = [];
        for ($i = 0; $i < 100000; $i++) {
            $list[] = new ValueObject($i);
        }
        foreach ($list as $item) {
            ObjectFunctions::getPropertyValue($item, 'value');
        }
    }
}