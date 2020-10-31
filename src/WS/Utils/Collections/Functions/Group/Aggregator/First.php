<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;

class First
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        foreach ($collection as $element) {
            return ObjectFunctions::getPropertyValue($element, $this->sourceKey);
        }

        return null;
    }
}
