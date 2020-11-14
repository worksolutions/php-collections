<?php
/**
 * @author Anton Lytkin <a.lytkin@worksolutions.ru>
 */

namespace WS\Utils\Collections;

use InvalidArgumentException;

class ArrayStrictList extends ArrayList
{
    protected function afterElementsSet(): void
    {
        foreach ($this->elements as $element) {
            $this->afterElementAdd($element);
        }
        parent::afterElementsSet();
    }

    protected function afterElementAdd($element): void
    {
        $firstElement = $this->elements[0];
        if (null === $firstElement && !count($this->elements)) {
            return;
        }
        if (is_object($firstElement)) {
            if (!is_object($element) || (get_class($firstElement) !== get_class($element))) {
                throw new InvalidArgumentException('Collection must contain elements with identical types');
            }
            return;
        }
        if (is_object($element) || (gettype($firstElement) !== gettype($element))) {
            throw new InvalidArgumentException('Collection must contain elements with identical types');
        }
        parent::afterElementAdd($element);
    }
}
