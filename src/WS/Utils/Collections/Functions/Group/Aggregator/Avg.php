<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;

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
            $acc += ObjectFunctions::getPropertyValue($element, $this->sourceKey);
            $cnt++;
        }
        if (!$cnt) {
            return null;
        }
        return $acc / $cnt;
    }
}
