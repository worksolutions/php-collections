<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class Sum extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        $sum = 0;
        foreach ($collection as $element) {
            $sum += $this->getValue($element);
        }
        return $sum;
    }
}
