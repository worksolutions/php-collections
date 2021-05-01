<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class Min extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        $min = null;
        foreach ($collection as $element) {
            $value = $this->getValue($element);
            if ($min === null || $min > $value) {
                $min = $value;
            }
        }
        return $min;
    }
}
