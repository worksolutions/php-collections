<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;

class First
{

    private $fieldName;

    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        if (!$first = $collection->stream()->findFirst()) {
            return null;
        }

        return ObjectFunctions::getPropertyValue($first, $this->fieldName);
    }
}
