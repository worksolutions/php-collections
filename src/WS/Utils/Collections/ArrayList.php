<?php
/**
 * @author Maxim Sokolovsky
 */

namespace WS\Utils\Collections;

class ArrayList extends AbstractList
{

    public function stream(): Stream
    {
        return new SerialStream($this);
    }
}
