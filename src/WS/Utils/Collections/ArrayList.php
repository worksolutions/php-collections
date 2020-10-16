<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

use OutOfRangeException;

class ArrayList extends AbstractCollection implements ListSequence
{

    public function stream(): Stream
    {
        return new SerialStream($this);
    }

    public function get(int $index)
    {
        return $this->elements[$index];
    }

    public function set($element, int $index)
    {
        if (!isset($this->elements[$index])) {
            throw new OutOfRangeException("Index $index is out of list range with size: {$this->size()} ");
        }
        $res = $this->elements[$index];
        $this->elements[$index] = $element;

        return $res;
    }

    public function indexOf($element): ?int
    {
        return array_search($element, $this->elements, true) ?: null;
    }

    public function remove($element): bool
    {
        if (is_object($element) && $element instanceof HashCodeAware) {
            return $this->removeThroughHashCode($element);
        }
        $key = array_search($element, $this->elements, true);
        if (false === $key) {
            return false;
        }
        $this->removeAt($key);
        return true;
    }

    public function lastIndexOf($element): ?int
    {
        $reverseIndex = array_search($element, array_reverse($this->elements), true);
        if ($reverseIndex === false) {
            return null;
        }

        return count($this->elements) - $reverseIndex - 1;
    }

    public function removeAt(int $index)
    {
        $size = $this->size();
        if ($index >= $size) {
            return null;
        }

        $el = $this->elements[$index];
        unset($this->elements[$index]);
        $this->pointer--;
        if ($this->pointer === -1) {
            return $el;
        }
        $this->elements = array_merge(
            array_slice($this->elements, 0, $index),
            array_slice($this->elements, $index)
        );
        return $el;
    }

    private function removeThroughHashCode(HashCodeAware $element): bool
    {
        foreach ($this->elements as $i => $iElement) {
            if ($iElement instanceof HashCodeAware && $iElement->getHashCode() === $element->getHashCode()) {
                $this->removeAt($i);
                return true;
            }
        }
        return false;
    }
}
