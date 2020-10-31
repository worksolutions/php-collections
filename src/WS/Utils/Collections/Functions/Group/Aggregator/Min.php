<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;

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
            $value = ObjectFunctions::getPropertyValue($element, $this->sourceKey);
            if ($min === null || $min > $value) {
                $min = $value;
            }
        }
        return $min;
    }
}
