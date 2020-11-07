<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class First extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        if (!$first = $collection->stream()->findFirst()) {
            return null;
        }

        return $this->getValue($first);
    }
}
