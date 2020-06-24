<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;


use RuntimeException;

class ArrayStack extends AbstractCollection implements Stack
{

    /**
     * Adds element to the top of stack
     *
     * @param $element
     *
     * @return bool
     */
    public function push($element): bool
    {
        return $this->add($element);
    }

    /**
     * Gets element from the top of stack
     *
     * @return mixed
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Stack is empty');
        }

        return array_pop($this->elements);
    }

    /**
     * Retrieves, but does not remove
     *
     * @return mixed
     */
    public function peek()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Stack is empty');
        }

        return $this->elements[count($this->elements) - 1];
    }

    public function merge(Collection $collection): bool
    {
        $this->elements = array_merge($this->elements, array_values($collection->toArray()));

        return true;
    }

    public function remove($element): bool
    {
        if ($this->isEmpty()) {
            return false;
        }
        $index = $this->size() - 1;
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
        while ($index >= 0) {
            if ($fMatch($this->elements[$index])) {
                unset($this->elements[$index]);
                $this->elements = array_values($this->elements);
                return true;
            }
            $index--;
        }

        return false;
    }

    public function stream(): Stream
    {
        return new SerialStream($this);
    }

    public function toArray(): array
    {
        return array_reverse($this->elements);
    }

}
