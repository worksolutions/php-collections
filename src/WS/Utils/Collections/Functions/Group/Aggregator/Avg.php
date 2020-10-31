<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

class Avg
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        $acc = null;
        $cnt = 0;
        foreach ($collection as $element) {
            $acc += $element[$this->sourceKey];
            $cnt++;
        }
        if (!$cnt) {
            return null;
        }
        return $acc / $cnt;
    }
}
