<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class Count implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        return $collection->size();
    }
}
