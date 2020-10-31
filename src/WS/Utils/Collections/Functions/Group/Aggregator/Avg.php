<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Avg
{

    private $fieldName;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        $acc = null;
        $cnt = 0;
        foreach ($collection as $element) {
            $acc += ObjectFunctions::getPropertyValue($element, $this->fieldName);
            $cnt++;
        }
        if (!$cnt) {
            return null;
        }
        return $acc / $cnt;
    }
}
