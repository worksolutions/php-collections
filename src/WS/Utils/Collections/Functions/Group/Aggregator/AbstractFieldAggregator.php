<?php
/**
 * @author Igor Pomiluyko <pomiluyko@worksolutions.ru>
 */

namespace WS\Utils\Collections\Functions\Group\Aggregator;

use WS\Utils\Collections\Functions\ObjectFunctions;

abstract class AbstractFieldAggregator
{

    private $fieldName;

    public function __construct(string $fieldName)
    {
        $this->fieldName = $fieldName;
    }

    protected function getValue($element)
    {
        return ObjectFunctions::getPropertyValue($element, $this->fieldName);
    }

}
