<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

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
        while ($indexIterator->hasNext()) {
            $index = $indexIterator->next();
            if ($fMatch($this->elements[$index])) {
                unset($this->elements[$index]);
                $this->elements = array_values($this->elements);
                return true;
            }
        }
        return false;
    }
}
