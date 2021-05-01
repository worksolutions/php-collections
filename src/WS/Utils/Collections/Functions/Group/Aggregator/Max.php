<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class Max extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        $max = null;
        foreach ($collection as $element) {
            $value = $this->getValue($element);
            if ($max === null || $max < $value) {
                $max = $value;
            }
        }
        return $max;
    }
}
