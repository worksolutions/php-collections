<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Min
{

    private $fieldName;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        $min = null;
        foreach ($collection as $element) {
            $value = ObjectFunctions::getPropertyValue($element, $this->fieldName);
            if ($min === null || $min > $value) {
                $min = $value;
            }
        }
        return $min;
    }
}
