<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

class Max
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        $max = null;
        foreach ($collection as $element) {
            if ($max === null || $max < $element[$this->sourceKey]) {
                $max = $element[$this->sourceKey];
            }
        }
        return $max;
    }
}
