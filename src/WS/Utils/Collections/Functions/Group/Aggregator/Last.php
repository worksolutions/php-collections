<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\CollectionFactory;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Last
{

    private $sourceKey;

    public function __construct($sourceKey)
    {
        $this->sourceKey = $sourceKey;
    }

    public function __invoke(iterable $collection)
    {
        $last = CollectionFactory::fromIterable($collection)->stream()->findLast();
        return ObjectFunctions::getPropertyValue($last, $this->sourceKey);
    }
}
