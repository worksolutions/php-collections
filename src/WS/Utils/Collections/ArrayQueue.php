<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;


use RuntimeException;

class ArrayQueue extends AbstractCollection implements Queue
{

    public function offer($element): bool
    {
        return $this->add($element);
    }

    public function poll()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Queue is empty');
        }

        return array_shift($this->elements);
    }

    public function peek()
    {
        if ($this->isEmpty()) {
            throw new RuntimeException('Queue is empty');
        }

        return $this->elements[0];
    }

    /**
     * Will be removed the first matched element from queue
     * @param $element
     * @return bool
     */
    public function remove($element): bool
    {
        $key = array_search($element, $this->elements, true);
        if ($key === false) {
            return false;
        }
        unset($this->elements[$key]);
        $this->elements = array_values($this->elements);
        return true;
    }

    public function stream(): Stream
    {
        return new SerialStream($this);
    }
}

