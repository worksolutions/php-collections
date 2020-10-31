<?php

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Collection;
use WS\Utils\Collections\Functions\ObjectFunctions;
use WS\Utils\Collections\HashSet;

class AddToSet
{

    private $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    public function __invoke(Collection $collection)
    {
        $set = new HashSet();
        foreach ($collection as $element) {
            $set->add(ObjectFunctions::getPropertyValue($element, $this->fieldName));
        }
        return $set->toArray();
    }
}
