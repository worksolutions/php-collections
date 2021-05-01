<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;

class Avg extends AbstractFieldAggregator implements Aggregator
{

    public function __invoke(Collection $collection)
    {
        $acc = null;
        $cnt = 0;
        foreach ($collection as $element) {
            $acc += $this->getValue($element);
            $cnt++;
        }
        if (!$cnt) {
            return null;
        }
        return $acc / $cnt;
    }
}
