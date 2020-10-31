<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

class Sum
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        $sum = null;
        foreach ($collection as $element) {
            $sum += $element[$this->sourceKey];
        }
        return $sum;
    }
}
