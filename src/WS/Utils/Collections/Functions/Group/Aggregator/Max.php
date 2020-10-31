<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;

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
            $value = ObjectFunctions::getPropertyValue($element, $this->sourceKey);
            if ($max === null || $max < $value) {
                $max = $value;
            }
        }
        return $max;
    }
}
