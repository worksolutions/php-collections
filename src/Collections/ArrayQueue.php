<?php
/**
 * @author  Igor Pomiluyko pomiluyko@worksolutions.ru
 * @license MIT
 */

namespace WS\Utils\Collections;


class ArrayQueue extends AbstractList implements Queue
{

    public function offer($element): bool
    {
        return $this->add($element);
    }

    public function poll()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return array_shift($this->elements);
    }

    public function peek()
    {
        if ($this->isEmpty()) {
            return null;
        }

        return $this->elements[0];
    }


    public function stream(): Stream
    {
        // TODO: Implement stream() method.
    }
}
