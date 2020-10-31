<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;
use WS\Utils\Collections\HashSet;

class AddToSet
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        $set = new HashSet();
        foreach ($collection as $element) {
            $set->add(ObjectFunctions::getPropertyValue($element, $this->sourceKey));
        }
        return $set->toArray();
    }
}
