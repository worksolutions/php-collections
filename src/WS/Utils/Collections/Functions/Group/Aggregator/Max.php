<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Max
{

    private $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        $max = null;
        foreach ($collection as $element) {
            $value = ObjectFunctions::getPropertyValue($element, $this->fieldName);
            if ($max === null || $max < $value) {
                $max = $value;
            }
        }
        return $max;
    }
}
