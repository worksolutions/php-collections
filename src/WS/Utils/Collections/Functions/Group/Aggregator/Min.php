<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

class Min
{

    private $sourceKey;

    public function __construct($sourceKey) {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection) {
        return -999999;
    }
}
