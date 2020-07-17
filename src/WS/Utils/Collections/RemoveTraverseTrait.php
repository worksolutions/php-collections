<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use WS\Utils\Collections\Iterator\Iterator;

trait RemoveTraverseTrait
{
    public function remove($element): bool
    {
        if ($this->isEmpty()) {
            return false;
        }
        $fMatch = static function ($tested) use ($element): bool {
            return $tested === $element;
        };
        if ($element instanceof HashCodeAware) {
            $fMatch = static function ($tested) use ($element): bool {
                if ($tested instanceof HashCodeAware) {
                    return $tested->getHashCode() === $element->getHashCode();
                }
                return $tested === $element;
            };
        }
        $indexIterator = $this->getIndexIterator();
        $elements = $this->getElements();
        while ($indexIterator->hasNext()) {
            $index = $indexIterator->next();
            if ($fMatch($elements[$index])) {
                unset($elements[$index]);
                $this->setElements(array_values($elements));
                return true;
            }
        }
        return false;
    }

    abstract public function getIndexIterator(): Iterator;

    abstract public function isEmpty(): bool;

    abstract protected function setElements(array $elements): void;

    abstract protected function getElements(): array;
}
