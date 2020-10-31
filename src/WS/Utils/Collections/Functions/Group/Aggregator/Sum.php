<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Sum
{

    private $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        $sum = 0;
        foreach ($collection as $element) {
            $sum += ObjectFunctions::getPropertyValue($element, $this->fieldName);
        }
        return $sum;
    }
}
