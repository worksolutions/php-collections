<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;

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
            if (!$value = ObjectFunctions::getPropertyValue($element, $this->sourceKey)) {
                continue;
            }
            $sum += $value;
        }
        return $sum;
    }
}
