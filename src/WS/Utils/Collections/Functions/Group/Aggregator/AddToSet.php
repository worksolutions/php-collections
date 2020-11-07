<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\HashSet;

class AddToSet extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        $set = new HashSet();
        foreach ($collection as $element) {
            $set->add($this->getValue($element));
        }
        return $set->toArray();
    }
}
