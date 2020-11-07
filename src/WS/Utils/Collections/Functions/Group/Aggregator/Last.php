<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class Last extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        if (!$last = $collection->stream()->findLast()) {
            return null;
        }
        return $this->getValue($last);
    }
}
