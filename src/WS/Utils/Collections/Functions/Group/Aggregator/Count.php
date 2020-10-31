<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\CollectionFactory;

class Count
{

    public function __invoke(iterable $collection)
    {
        return CollectionFactory::fromIterable($collection)->size();
    }
}
