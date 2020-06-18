<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

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

    public function set($el, int $index)
    {
        $res = $this->elements[$index];
        $this->elements[$index] = $el;

        return $res;
    }

    public function indexOf($el)
    {
        return array_search($el, $this->elements, true);
    }

    public function lastIndexOf($el)
    {
        $reverseIndex = array_search($el, array_reverse($this->elements), true);
        if ($reverseIndex === false) {
            return false;
        }

        return count($el) - $reverseIndex - 1;
    }

    public function removeAt(int $index)
    {
        $size = $this->size();
        if (!isset($this->elements[$index]) || $size < $index + 1) {
            return null;
        }

        $el = $this->elements[$index];
        unset($this->elements[$index]);
        $this->elements = array_values($this->elements);

        return $el;
    }
}
