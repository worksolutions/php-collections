<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

class Min
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        $min = null;
        foreach ($collection as $element) {
            if ($min === null || $min > $element[$this->sourceKey]) {
                $min = $element[$this->sourceKey];
            }
        }
        return $min;
    }
}
