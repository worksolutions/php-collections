<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;

class Last
{

    private $fieldName;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        if (!$last = $collection->stream()->findLast()) {
            return null;
        }
        return ObjectFunctions::getPropertyValue($last, $this->fieldName);
    }
}
